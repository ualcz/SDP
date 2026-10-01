<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

class TurmaAlunoScraper
{
    /**
     * Extrai o código da turma atual do aluno a partir da tabela em tab=dados_academicos.
     *
     * Estrutura da tabela:
     * <thead><tr><th>Período</th><th>Ano/Período Letivo</th><th>Turma</th><th>Situação no Período</th></tr></thead>
     * <tbody><tr><td>3</td><td>2026/1</td><td>20261.3.18.1I</td><td>Matriculado</td></tr>...</tbody>
     */
    public function codigoAtual(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            // 1. Procura na tabela de histórico de períodos/turmas acadêmicas
            $tabelas = $crawler->filter('table');
            foreach ($tabelas as $tableElement) {
                $tableCrawler = new Crawler($tableElement);

                // Mapeia índices das colunas a partir do <thead>
                $headers = [];
                $tableCrawler->filter('thead tr th')->each(function (Crawler $th, $i) use (&$headers) {
                    $headers[trim(mb_strtolower($th->text()))] = $i;
                });

                $turmaColIndex = null;
                $situacaoColIndex = null;

                foreach ($headers as $headerText => $index) {
                    if (str_contains($headerText, 'turma')) {
                        $turmaColIndex = $index;
                    }
                    if (str_contains($headerText, 'situaç') || str_contains($headerText, 'situacao')) {
                        $situacaoColIndex = $index;
                    }
                }

                if ($turmaColIndex !== null) {
                    $linhas = $tableCrawler->filter('tbody tr');

                    // Passo 1: Busca a linha com situação "Matriculado", "Ativo", "Cursando", etc.
                    foreach ($linhas as $tr) {
                        $trCrawler = new Crawler($tr);
                        $tds = $trCrawler->filter('td');

                        if ($tds->count() > $turmaColIndex) {
                            $situacao = $situacaoColIndex !== null && $tds->count() > $situacaoColIndex
                                ? trim(mb_strtolower($tds->eq($situacaoColIndex)->text()))
                                : '';

                            if (preg_match('/matriculado|ativo|regular|cursando|em curso|inscrito|vinculado/i', $situacao)) {
                                $turmaTexto = trim($tds->eq($turmaColIndex)->text());
                                if (!empty($turmaTexto)) {
                                    return $turmaTexto;
                                }
                            }
                        }
                    }

                    // Passo 2: Se não houver linha explicitamente como "Matriculado", pega a primeira linha (mais recente)
                    if ($linhas->count() > 0) {
                        $primeiraLinha = $linhas->first();
                        $tds = (new Crawler($primeiraLinha))->filter('td');
                        if ($tds->count() > $turmaColIndex) {
                            $turmaTexto = trim($tds->eq($turmaColIndex)->text());
                            if (!empty($turmaTexto)) {
                                return $turmaTexto;
                            }
                        }
                    }
                }
            }

            // 2. Fallback geral: busca por linhas de tabelas onde a situação seja matriculado
            $linhas = $crawler->filter('table tbody tr');
            foreach ($linhas as $tr) {
                $linha = new Crawler($tr);
                $textoLinha = mb_strtolower($linha->text());

                $ehMatriculado = preg_match('/matriculado|ativo|regular|cursando/i', $textoLinha) === 1;

                $colunas = $linha->filter('td');
                foreach ($colunas as $coluna) {
                    $txt = trim($coluna->textContent);
                    // Padrão de código de turma SUAP (ex: 20261.3.18.1I ou 20251.2.18.1I)
                    if (preg_match('/^[0-9]{4,}[A-Za-z0-9\.\-_]+$/', $txt) && strlen($txt) >= 5) {
                        if ($ehMatriculado) {
                            return $txt;
                        }
                    }
                }
            }

            // 3. Fallback para elementos de texto com "Turma:"
            $elementos = $crawler->filter('p, div, span, td');
            foreach ($elementos as $el) {
                $txt = trim($el->textContent);
                if (preg_match('/Turma:\s*([A-Za-z0-9\.\-_]+)/i', $txt, $matches)) {
                    return trim($matches[1]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Erro no scraping de turma do aluno: ' . $e->getMessage());
        }

        return null;
    }
}

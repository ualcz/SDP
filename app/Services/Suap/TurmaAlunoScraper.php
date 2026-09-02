<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class TurmaAlunoScraper
{
    /**
     * Extrai o código da turma atual do aluno
     */
    public function codigoAtual(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            // Varre as linhas da tabela de matrículas/turmas do aluno
            $linhas = $crawler->filter('table tbody tr');
            if ($linhas->count() > 0) {
                foreach ($linhas as $tr) {
                    $linha = new Crawler($tr);
                    $textoLinha = strtolower($linha->text());
                    $statusValido = preg_match('/matriculado|ativo|regular|cursando|em curso|inscrito|vinculado/i', $textoLinha) === 1;

                    $colunas = $linha->filter('td');
                    foreach ($colunas as $coluna) {
                        $txt = trim($coluna->textContent);

                        // Identifica códigos de turma típicos (ex: 2024.1, 2025.1, 2026.1-INFO, etc.)
                        if (preg_match('/^[0-9]{4,}[A-Za-z0-9\.\-_]*$/', $txt) && strlen($txt) >= 4) {
                            // Não depende só de "Ativo/Matriculado"; há turmas em outros estados de matrícula.
                            if ($statusValido || preg_match('/\d{4,}/', $txt)) {
                                return $txt;
                            }
                        }
                    }
                }
            }

            // Busca alternativa por texto 'Turma:' ou 'Curso:'
            $elementos = $crawler->filter('p, div, span, td');
            foreach ($elementos as $el) {
                $txt = trim($el->textContent);
                if (preg_match('/Turma:\s*([A-Za-z0-9\.\-_]+)/i', $txt, $matches)) {
                    return trim($matches[1]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erro no scraping de turma do aluno: ' . $e->getMessage());
        }

        return null;
    }
}

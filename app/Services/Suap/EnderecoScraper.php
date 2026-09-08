<?php

namespace App\Services\Suap;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class EnderecoScraper
{
    /**
     * Extrai o endereço a partir da página de dados pessoais do SUAP e retorna estruturado
     *
     * @return array{rua: ?string, numero: ?string, bairro: ?string, cep: ?string, cidade: ?string, estado: ?string}|null
     */
    public function extrair(?Crawler $crawler): ?array
    {
        $texto = $this->extrairTexto($crawler);

        if (!$texto) {
            return null;
        }

        return self::parse($texto);
    }

    /**
     * Extrai a string bruta de endereço do HTML do SUAP
     */
    public function extrairTexto(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            // Busca a célula <td> que contém 'Endereço' e pega a primeira célula <td> seguinte
            $enderecoNode = $crawler->filterXPath("//td[contains(normalize-space(text()), 'Endereço')]/following-sibling::td[1]");

            if ($enderecoNode->count() > 0) {
                return trim($enderecoNode->text());
            }
        } catch (\Throwable $e) {
            Log::warning('Erro no scraping de endereço: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Faz o parse da string de endereço do SUAP para os campos individuais.
     * Exemplo: "Rua Antonio Francisco, 60, Cabralia, 46765-000, Piata-Ba"
     *
     * @return array{rua: ?string, numero: ?string, bairro: ?string, cep: ?string, cidade: ?string, estado: ?string}|null
     */
    public static function parse(?string $raw): ?array
    {
        if (empty($raw)) {
            return null;
        }

        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }

        $partes = array_values(array_filter(array_map('trim', explode(',', $raw)), fn($p) => $p !== ''));

        if (empty($partes)) {
            return null;
        }

        $dados = [
            'rua' => null,
            'numero' => null,
            'bairro' => null,
            'cep' => null,
            'cidade' => null,
            'estado' => null,
        ];

        // Padrão padrão do SUAP com 5 partes: [Rua, Número, Bairro, CEP, Cidade-UF]
        if (count($partes) === 5) {
            // Rua e Número no mesmo campo
            $dados['rua'] = $partes[0] . ', ' . $partes[1];
            $dados['numero'] = $partes[1];
            $dados['bairro'] = $partes[2];
            $dados['cep'] = $partes[3];

            $cidadeUf = $partes[4];
            if (preg_match('#^(.*?)\s*[-/]\s*([A-Za-z]{2})$#i', $cidadeUf, $matches)) {
                $dados['cidade'] = trim($matches[1]);
                $dados['estado'] = strtoupper(trim($matches[2]));
            } else {
                $dados['cidade'] = $cidadeUf;
            }

            return $dados;
        }

        // Caso dinâmico (quantidade de partes diferente de 5):
        // 1. Procurar CEP por regex
        foreach ($partes as $idx => $parte) {
            if (preg_match('/^\d{5}-?\d{3}$/', $parte)) {
                $dados['cep'] = $parte;
                unset($partes[$idx]);
                break;
            }
        }

        // 2. Procurar Cidade-UF (normalmente último elemento)
        $partes = array_values($partes);
        if (!empty($partes)) {
            $ultimo = end($partes);
            if (preg_match('#^(.*?)\s*[-/]\s*([A-Za-z]{2})$#i', $ultimo, $matches)) {
                $dados['cidade'] = trim($matches[1]);
                $dados['estado'] = strtoupper(trim($matches[2]));
                array_pop($partes);
            }
        }

        // 3. Atribuir o restante às posições correspondentes
        $partes = array_values($partes);
        if (count($partes) >= 1) {
            $dados['rua'] = array_shift($partes);
        }
        if (count($partes) >= 1) {
            if (preg_match('/^(\d+|s\/?n|sem n[úu]mero)$/i', $partes[0])) {
                $dados['numero'] = array_shift($partes);
                $dados['rua'] .= ', ' . $dados['numero'];
            }
        }
        if (count($partes) >= 1) {
            $dados['bairro'] = implode(', ', $partes);
        }

        return $dados;
    }
}
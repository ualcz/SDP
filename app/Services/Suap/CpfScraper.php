<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class CpfScraper
{
    /**
     * Extrai o CPF a partir da página de dados pessoais do SUAP
     */
    public function extrair(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            // Estratégia: Buscando célula por célula ou usando XPath direto no Crawler
            $cpfNode = $crawler->filterXPath("//td[normalize-space(text())='CPF']/following-sibling::td[1]");

            if ($cpfNode->count() > 0) {
                $valor = trim($cpfNode->text());
                
                $cpf = preg_replace('/\D/', '', $valor);

                if (strlen($cpf) === 11) {
                    return preg_replace(
                        '/^(\d{3})(\d{3})(\d{3})(\d{2})$/',
                        '$1.$2.$3-$4',
                        $cpf
                    );
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erro no scraping de CPF: ' . $e->getMessage());
        }

        return null;
    }
}
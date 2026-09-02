<?php

namespace App\Services\Suap;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class EnderecoScraper
{
    /**
     * Extrai o endereço a partir da página de dados pessoais do SUAP
     */
    public function extrair(?Crawler $crawler): ?string
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
}
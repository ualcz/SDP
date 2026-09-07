<?php

namespace App\Services\Suap;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class TelefoneScraper
{
    /**
     * Extrai o telefone a partir da página de dados pessoais do SUAP
     */
    public function extrair(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            // Busca a célula <td> que contém 'Telefone' e pega a primeira célula <td> seguinte
            $telefoneNode = $crawler->filterXPath("//td[contains(normalize-space(text()), 'Telefone')]/following-sibling::td[1]");

            if ($telefoneNode->count() > 0) {
                $telefone = trim($telefoneNode->text());
                
                // Retorna nulo caso o valor extraído seja apenas um traço '-' ou esteja vazio
                return ($telefone !== '' && $telefone !== '-') ? $telefone : null;
            }
        } catch (\Throwable $e) {
            Log::warning('Erro no scraping de telefone: ' . $e->getMessage());
        }

        return null;
    }
}
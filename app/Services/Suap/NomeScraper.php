<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class NomeScraper
{
    /**
     * Extrai o nome a partir da tabela de dados pessoais do SUAP
     */
    public function extrair(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            // Busca a célula com o texto 'Nome' e pega o próximo <td>
            $nomeNode = $crawler->filterXPath("//td[normalize-space(text())='Nome']/following-sibling::td[1]");

            if ($nomeNode->count() > 0) {
                $valor = trim($nomeNode->text());
                
                return $valor !== '' ? $valor : null;
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erro no scraping de nome: ' . $e->getMessage());
        }

        return null;
    }
}
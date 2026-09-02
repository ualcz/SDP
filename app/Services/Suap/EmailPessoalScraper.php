<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class EmailPessoalScraper
{
    /**
     * Extrai o e-mail pessoal a partir da página de dados pessoais do SUAP
     */
    public function extrair(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            
           // Estratégia corrigida: Buscando célula por célula ou usando XPath direto no Crawler
            $emailNode = $crawler->filterXPath("//td[normalize-space(text())='E-mail Pessoal']/following-sibling::td[1]");

            if ($emailNode->count() > 0) {
                $valor = trim($emailNode->text());
                
                if (filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    if (!str_ends_with(strtolower($valor), '@ifba.edu.br')) {
                        return $valor;
                    }
                }
            }


            // Estratégia 2: Busca campos de input/readonly
            $inputs = $crawler->filter('input[name*="email"], input[id*="email"]');
            foreach ($inputs as $inputDom) {
                $val = trim($inputDom->getAttribute('value') ?? '');
                if (filter_var($val, FILTER_VALIDATE_EMAIL) && !str_ends_with(strtolower($val), '@ifba.edu.br')) {
                    return $val;
                }
            }

            // Estratégia 3: Varredura de todos os e-mails na página que não sejam @ifba.edu.br
            $textoGeral = $crawler->html();
            if (preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $textoGeral, $matches)) {
                foreach ($matches[0] as $emailEncontrado) {
                    $emailEncontrado = trim($emailEncontrado);
                    if (filter_var($emailEncontrado, FILTER_VALIDATE_EMAIL) && !str_ends_with(strtolower($emailEncontrado), '@ifba.edu.br')) {
                        return $emailEncontrado;
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erro no scraping de email pessoal: ' . $e->getMessage());
        }

        return null;
    }
}

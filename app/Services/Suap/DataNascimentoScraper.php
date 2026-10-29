<?php

namespace App\Services\Suap;

use DateTimeImmutable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class DataNascimentoScraper
{
    public function extrair(?Crawler $crawler): ?string
    {
        if (!$crawler) {
            return null;
        }

        try {
            $nascimento = $crawler->filterXPath(
                "//td[normalize-space(.)='Nascimento']/following-sibling::td[1]"
            );
            if ($nascimento->count() === 0) {
                return null;
            }

            $valor = trim(preg_replace('/\s+/', ' ', $nascimento->first()->text()) ?? '');
            $data = DateTimeImmutable::createFromFormat('!d/m/Y', $valor);
            $erros = DateTimeImmutable::getLastErrors();

            if (
                !$data
                || ($erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0))
                || $data->format('d/m/Y') !== $valor
            ) {
                return null;
            }

            return $data->format('Y-m-d');
        } catch (\Throwable $exception) {
            Log::warning('Erro ao extrair data de nascimento do SUAP: ' . $exception->getMessage());

            return null;
        }
    }
}

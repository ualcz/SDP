<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class TurmaAlunoScraper
{
    public function codigoAtual(Crawler $crawler): ?string
    {
        foreach ($crawler->filter('table tbody tr') as $tr) {

            $linha = new Crawler($tr);

            $situacao = trim(
                $linha->filter('td:nth-child(4)')->text()
            );

            if ($situacao === 'Matriculado') {

                return trim(
                    $linha->filter('td:nth-child(3)')->text()
                );
            }
        }

        return null;
    }
}
<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class TurmaScraper
{
    public function listar(Crawler $crawler): array
    {
        $turmas = [];

        $crawler
            ->filter('table tbody tr')
            ->each(function (Crawler $linha) use (&$turmas) {

                $codigo = trim(
                    $linha->filter('td:nth-child(1)')->text()
                );

                $nome = trim(
                    $linha->filter('td:nth-child(2)')->text()
                );

                $href = $linha
                    ->filter('a')
                    ->attr('href');

                preg_match(
                    '#/sala_virtual/(\d+)/#',
                    $href,
                    $match
                );

                $turmas[] = [
                    'id' => (int) $match[1],
                    'codigo' => $codigo,
                    'nome' => $nome,
                    'url' => $href,
                ];
            });

        return $turmas;
    }
}
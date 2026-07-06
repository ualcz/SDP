<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class ProfessorScraper
{
    public function extrair(Crawler $crawler): array
    {
        $professores = [];

        $crawler
            ->filter('.box-info')
            ->each(function (Crawler $box) use (&$professores) {

                if (!$box->filter('h4')->count()) {
                    return;
                }

                $professores[] = trim($box->filter('h4')->text());
            });

        return $professores;
    }
}
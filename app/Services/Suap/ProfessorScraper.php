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

                $nome = trim(
                    $box->filter('h4')->text()
                );

                $matricula = null;

                $dds = $box->filter('dd');

                if ($dds->count() > 0) {
                    $matricula = trim(
                        $dds->eq(0)->text()
                    );
                }

                $professores[] = [
                    'nome' => $nome,
                    'matricula' => $matricula,
                ];
            });

        return $professores;
    }
}
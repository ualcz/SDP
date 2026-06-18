<?php
//Ele vai receber páginas do SuapWebService e extrair as informações.
namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class SuapCrawler
{
    public function listarSalas(string $html): array
    {
        $crawler = new Crawler($html);

        $salas = [];

        /*
         * Por enquanto só vamos descobrir
         * como localizar cada sala.
         */

        $crawler->filter('a')->each(function ($node) use (&$salas) {

            $href = $node->attr('href');

            if (!$href) {
                return;
            }

            if (preg_match('#/edu/sala_virtual/(\d+)/#', $href, $match)) {

                $salas[] = [

                    'id' => $match[1],

                    'titulo' => trim($node->text()),

                    'url' => $href

                ];

            }

        });

        return array_unique($salas, SORT_REGULAR);
    }
}
?>
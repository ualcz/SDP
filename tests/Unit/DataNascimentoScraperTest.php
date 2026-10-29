<?php

use App\Services\Suap\DataNascimentoScraper;
use Symfony\Component\DomCrawler\Crawler;

it('extracts and validates the student birth date from SUAP personal data', function () {
    $scraper = new DataNascimentoScraper();
    $crawler = new Crawler(
        '<table><tbody><tr><td>Nascimento</td><td>30/06/2004</td></tr></tbody></table>'
    );

    expect($scraper->extrair($crawler))->toBe('2004-06-30')
        ->and($scraper->extrair(new Crawler('<table><tr><td>Nascimento</td><td>-</td></tr></table>')))->toBeNull()
        ->and($scraper->extrair(new Crawler('<table><tr><td>Nascimento</td><td>31/02/2004</td></tr></table>')))->toBeNull()
        ->and($scraper->extrair(new Crawler('<table><tr><td>Nome</td><td>Ana</td></tr></table>')))->toBeNull();
});

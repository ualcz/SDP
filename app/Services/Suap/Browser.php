<?php

namespace App\Services\Suap;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class Browser
{
    private HttpBrowser $browser;

    public function __construct()
    {
        $this->browser = new HttpBrowser(
            HttpClient::create([
                'headers' => [
                    'User-Agent' =>
                        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/149 Safari/537.36',
                ]
            ])
        );
    }

    /**
     * Faz login no SUAP
     */
    public function login(string $matricula, string $senha): bool
    {
        $crawler = $this->browser->request(
            'GET',
            'https://suap.ifba.edu.br/accounts/login/'
        );

        $form = $crawler->selectButton('Acessar')->form([
            'username' => $matricula,
            'password' => $senha,
        ]);

        $crawler = $this->browser->submit($form);

        return !$crawler->filter('body')->attr('class') ||
               !str_contains(
                    $crawler->filter('body')->attr('class'),
                    'login'
               );
    }

    /**
     * Abre qualquer página autenticada
     */
    public function get(string $url): Crawler
    {
        return $this->browser->request(
            'GET',
            'https://suap.ifba.edu.br'.$url
        );
    }

    /**
     * Retorna o BrowserKit
     */
    public function browser(): HttpBrowser
    {
        return $this->browser;
    }
}
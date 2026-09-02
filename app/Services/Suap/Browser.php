<?php

namespace App\Services\Suap;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class Browser
{
    private HttpBrowser $browser;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.suap_base_url', 'https://suap.ifba.edu.br');

        $this->browser = new HttpBrowser(
            HttpClient::create([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ],
                'verify_peer' => false,
                'verify_host' => false,
                'timeout' => 15,
            ])
        );
    }

    /**
     * Realiza login no SUAP via formulário Web
     */
    public function login(string $matricula, string $senha): bool
    {
        try {
            $crawler = $this->browser->request('GET', rtrim($this->baseUrl, '/') . '/accounts/login/');

            $button = $crawler->selectButton('Acessar');
            if ($button->count() === 0) {
                $button = $crawler->filter('input[type="submit"], button[type="submit"]');
            }

            if ($button->count() === 0) {
                return false;
            }

            $form = $button->form([
                'username' => $matricula,
                'password' => $senha,
            ]);

            $crawler = $this->browser->submit($form);

            $body = $crawler->filter('body');
            if ($body->count() === 0) {
                return false;
            }

            $bodyClass = $body->attr('class') ?? '';
            return !str_contains($bodyClass, 'login');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Falha ao autenticar no SUAP via Web Scraping: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Acessa página autenticada
     */
    public function get(string $url): ?Crawler
    {
        try {
            $fullUrl = str_starts_with($url, 'http') ? $url : rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');
            return $this->browser->request('GET', $fullUrl);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao acessar URL no SUAP: ' . $e->getMessage());
            return null;
        }
    }

    public function browser(): HttpBrowser
    {
        return $this->browser;
    }
}

<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class SuapWebService
{
    private string $baseUrl = 'https://suap.ifba.edu.br';

    private Client $client;

    private CookieJar $cookies;

    public function __construct()
    {
        $this->cookies = new CookieJar();

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'cookies' => $this->cookies,
            'allow_redirects' => true,
            'verify' => true,
            'http_errors' => false,
            'headers' => [
                'User-Agent' =>
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/137 Safari/537.36',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Login WEB
    |--------------------------------------------------------------------------
    */

    public function login(string $matricula, string $senha): bool
    {
        /*
         * 1) Abre a tela de login
         */

        $response = $this->client->get('/accounts/login/');

        $html = (string) $response->getBody();

        /*
         * 2) Extrai o CSRF da página
         */

        preg_match(
            '/name="csrfmiddlewaretoken" value="([^"]+)"/',
            $html,
            $match
        );

        if (!isset($match[1])) {

            return false;

        }

        $csrf = $match[1];

        /*
         * 3) Faz o POST exatamente igual ao navegador
         */

        $login = $this->client->post('/accounts/login/', [

            'form_params' => [

                'csrfmiddlewaretoken' => $csrf,

                'username' => $matricula,

                'password' => $senha,

                'this_is_the_login_form' => 1,

                'next' => '',

            ],

            'headers' => [

                'Referer' => $this->baseUrl.'/accounts/login/',

            ]

        ]);

        /*
         * 4) Testa se entrou
         */

        $pagina = (string) $login->getBody();

        return !str_contains($pagina, 'Usuário:');
    }

    /*
    |--------------------------------------------------------------------------
    | GET autenticado
    |--------------------------------------------------------------------------
    */

    public function get(string $url): string
    {
        $response = $this->client->get($url);

        return (string) $response->getBody();
    }
}
?>
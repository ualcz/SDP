<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class SuapService
{
    private string $baseUrl = 'https://suap.ifba.edu.br/api/v2';

    /*
    |--------------------------------------------------------------------------
    | Obtém JWT
    |--------------------------------------------------------------------------
    */
    public function autenticar($matricula, $senha)
{
    try {

        $response = Http::asJson()
            ->timeout(10)
            ->post($this->baseUrl . '/autenticacao/token/', [
                'username' => $matricula,
                'password' => $senha,
            ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json()['token'] ?? null;

    } catch (ConnectionException $e) {

        return null;
    }
}

    /*
    |--------------------------------------------------------------------------
    | Busca dados do usuário autenticado
    |--------------------------------------------------------------------------
    */
    public function meusDados($jwt)
{
    try {

        $response = Http::withHeaders([
            'Authorization' => 'JWT ' . $jwt,
            'Content-Type'  => 'application/json',
        ])->get(
            $this->baseUrl . '/minhas-informacoes/meus-dados/'
        );

        if ($response->failed()) {
            return null;
        }

        return $response->json();

    } catch (ConnectionException $e) {

        return null;
    }
}
}
?>
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lista de Setores e E-mails Destinatários
    |--------------------------------------------------------------------------
    |
    | Para adicionar um novo setor no futuro, basta adicionar uma nova entrada
    | neste array ou definir a variável correspondente no arquivo .env.
    |
    */
    'destinatarios' => [
        'teste1' => [
            'nome' => 'Teste1',
            'email' => env('EMAIL_TESTE_1', 'teste1'),
        ],
        'teste2' => [
            'nome' => 'Teste2',
            'email' => env('EMAIL_TESTE_2', 'teste2'),
        ]
    ],
];

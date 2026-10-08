<?php

return [
    'required' => 'O campo :attribute é obrigatório.',

    'attributes' => [
        'descricao' => 'descrição',
        'objetoDoRequerimento' => 'objeto do requerimento',
        'motivo' => 'justificativa',
        'documentos.*.nome' => 'nome do documento',
        'nome' => 'nome',
        'email' => 'e-mail',
        'password' => 'senha',
    ],
];

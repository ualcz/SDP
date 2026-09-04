<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Modelos de Requerimentos por Setor (IFBA Campus Seabra)
    |--------------------------------------------------------------------------
    |
    | Aqui você pode cadastrar e customizar os diferentes modelos de formulários
    | de requerimento para cada setor. Para adicionar um novo setor ou modelo,
    | basta criar uma nova chave neste array com suas respectivas opções.
    |
    */

    'modelos' => [
        // 1. Modelo CORES (Registro Escolar)
        'cores' => [
            'identificador' => 'cores',
            'setor_chave' => 'teste1', // Chave em config/setores.php
            'setor_sigla' => 'CORES',
            'setor_nome' => 'Coordenação de Registro Escolares',
            'email' => env('EMAIL_CORES'),
            'titulo' => 'Requerimento - Registro Escolar (CORES)',
            'processo_prefixo' => '23720',
            'objetos' => [
                '01' => 'Atestado/Declaração de Conclusão (01)*',
                '02' => 'Atestado de Matrícula e/ou Frequência (02)',
                '03' => 'Certificação ENEM/ENCCEJA (03)',
                '04' => 'Histórico Escolar (04)*',
                '05' => 'Cancelamento de matrícula (05)*',
                '06' => 'Solicitação de Diploma (06)* **',
                '07' => 'Justificativa de Faltas (07)***',
                '08' => 'Trancamento de matrícula (08)* **',
                '09' => 'Transferência Interna – entre as unidades do IFBA (09)*',
                '10' => 'Certificado de Conclusão do Ensino Médio (10)*',
                '11' => 'Outros (11)',
            ],
            'observacoes' => [
                '* Necessitam do "nada consta" da biblioteca para serem realizados e Dacad',
                '** Necessitam de nº de processo',
                '*** Necessitam de atestado Médico',
            ],
            'rodape_contato' => env('EMAIL_CORES'),
        ],

        // 2. Modelo COTEP (Coordenação Técnico-Pedagógica)
        'cotep' => [
            'identificador' => 'cotep',
            'setor_chave' => 'teste2',
            'setor_sigla' => 'COTEP',
            'setor_nome' => 'Coordenação de Ensino',
            'email' => env('EMAIL_COTEP'),
            'titulo' => 'Requerimento - Pedagógico / Assistência Estudantil (COTEP)',
            'processo_prefixo' => '23720',
            'objetos' => [
                '01' => 'Solicitação de Atendimento Psicopedagógico (01)',
                '02' => 'Inscrição / Atualização em Programas de Assistência Estudantil (02)',
            ],
            'observacoes' => [
                '* Casos de saúde ou vulnerabilidade podem requerer documentação comprobatória em anexo.',
            ],
            'rodape_contato' => env('EMAIL_COTEP'),
        ],
    ],
];

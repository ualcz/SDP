<?php

namespace Database\Seeders;

use App\Models\AssuntoRequerimento;
use App\Models\ModeloRequerimento;
use Illuminate\Database\Seeder;

class ModeloRequerimentoSeeder extends Seeder
{
    public function run(): void
    {
        $modelosConfig = config('modelos_requerimentos.modelos', []);

        foreach ($modelosConfig as $chave => $dados) {
            $modelo = ModeloRequerimento::updateOrCreate(
                ['identificador' => $dados['identificador'] ?? $chave],
                [
                    'setor_chave' => $dados['setor_chave'] ?? 'teste1',
                    'setor_sigla' => $dados['setor_sigla'] ?? strtoupper($chave),
                    'setor_nome' => $dados['setor_nome'] ?? 'Setor Responsável',
                    'email' => $dados['email'] ?? null,
                    'titulo' => $dados['titulo'] ?? 'Requerimento',
                    'processo_prefixo' => $dados['processo_prefixo'] ?? '23720',
                    'rodape_contato' => $dados['rodape_contato'] ?? null,
                    'ativo' => true,
                ]
            );

            // Mapeamento de notas por asteriscos para o setor CORES
            $notasCores = [
                '***' => 'Necessitam de atestado Médico',
                '* **' => 'Necessitam do "nada consta" da biblioteca para serem realizados e Dacad; Necessitam de nº de processo',
                '*' => 'Necessitam do "nada consta" da biblioteca para serem realizados e Dacad',
            ];

            $ordem = 1;
            foreach ($dados['objetos'] ?? [] as $codigo => $descricao) {
                $obsAssunto = null;

                if ($chave === 'cores') {
                    if (str_contains($descricao, '***')) {
                        $obsAssunto = $notasCores['***'];
                    } elseif (str_contains($descricao, '* **')) {
                        $obsAssunto = $notasCores['* **'];
                    } elseif (str_contains($descricao, '*')) {
                        $obsAssunto = $notasCores['*'];
                    }
                } elseif ($chave === 'cotep') {
                    if (str_contains($descricao, '*')) {
                        $obsAssunto = 'Casos de saúde ou vulnerabilidade podem requerer documentação comprobatória em anexo.';
                    }
                }

                AssuntoRequerimento::updateOrCreate(
                    [
                        'modelo_requerimento_id' => $modelo->id,
                        'codigo' => (string) $codigo,
                    ],
                    [
                        'descricao' => $descricao,
                        'observacao' => $obsAssunto,
                        'ordem' => $ordem++,
                        'ativo' => true,
                    ]
                );
            }
        }
    }
}

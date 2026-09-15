<?php

namespace Database\Seeders;

use App\Models\AssuntoRequerimento;
use App\Models\DocumentoAssunto;
use App\Models\Setor;
use Illuminate\Database\Seeder;

class DocumentosAssuntoSeeder extends Seeder
{
    /**
     * Documentos obrigatórios mapeados por código do assunto e identificador do modelo.
     *
     * Estrutura:
     *   'identificador_modelo' => [
     *       'codigo_assunto' => [
     *           ['nome' => '...', 'descricao' => '...', 'tipos_aceitos' => '...'],
     *       ],
     *   ]
     */
    private array $documentosPorAssunto = [
        'cores' => [
            '01' => [ // Atestado/Declaração de Conclusão
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
            ],
            '04' => [ // Histórico Escolar
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
            ],
            '05' => [ // Cancelamento de matrícula
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
            ],
            '06' => [ // Solicitação de Diploma
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
                ['nome' => 'Comprovante de Nº de Processo', 'descricao' => 'Número de processo SEI ou SUAP relacionado à solicitação de diploma.'],
            ],
            '07' => [ // Justificativa de Faltas
                ['nome' => 'Atestado Médico', 'descricao' => 'Atestado médico original com CRM do médico, período de afastamento e assinatura.', 'tipos_aceitos' => 'pdf,jpg,jpeg,png'],
            ],
            '08' => [ // Trancamento de matrícula
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
                ['nome' => 'Comprovante de Nº de Processo', 'descricao' => 'Número de processo SEI ou SUAP relacionado ao trancamento.'],
            ],
            '09' => [ // Transferência Interna
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
            ],
            '10' => [ // Certificado de Conclusão do Ensino Médio
                ['nome' => 'Nada Consta da Biblioteca', 'descricao' => 'Documento emitido pela biblioteca do campus confirmando que não há pendências.'],
                ['nome' => 'Dacad',                     'descricao' => 'Declaração de ausência de débitos acadêmicos e administrativos.'],
            ],
            // 02, 03, 11 — sem documentos obrigatórios
        ],
        'cotep' => [
            // 01, 02 — sem documentos obrigatórios por padrão
            // Casos de saúde/vulnerabilidade são tratados como arquivo opcional
        ],
    ];

    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1. Garante que os modelos e assuntos existem no banco
        //    (sincroniza a partir do config, caso o banco esteja vazio)
        // ---------------------------------------------------------------
        $modelos = config('modelos_requerimentos.modelos', []);

        foreach ($modelos as $modeloConfig) {
            $sigla = $modeloConfig['setor_sigla'] ?? strtoupper($modeloConfig['identificador'] ?? '');
            $modelo = Setor::updateOrCreate(
                ['setor_sigla' => $sigla],
                [
                    'setor_nome'       => $modeloConfig['setor_nome'],
                    'email'            => $modeloConfig['email'] ?? null,
                    'titulo'           => $modeloConfig['titulo'],
                    'processo_prefixo' => $modeloConfig['processo_prefixo'] ?? '23720',
                    'ativo'            => true,
                ]
            );

            $ordem = 1;
            foreach ($modeloConfig['objetos'] ?? [] as $codigo => $descricao) {
                AssuntoRequerimento::updateOrCreate(
                    [
                        'setor_id' => $modelo->id,
                        'codigo'   => $codigo,
                    ],
                    [
                        'descricao' => $descricao,
                        'ordem'     => $ordem++,
                        'ativo'     => true,
                    ]
                );
            }
        }

        // ---------------------------------------------------------------
        // 2. Popula os documentos obrigatórios por assunto
        // ---------------------------------------------------------------
        foreach ($this->documentosPorAssunto as $identificadorModelo => $assuntos) {
            $modelo = Setor::where('setor_sigla', strtoupper($identificadorModelo))->first();

            if (!$modelo) {
                $this->command->warn("Modelo '{$identificadorModelo}' não encontrado. Pulando...");
                continue;
            }

            foreach ($assuntos as $codigoAssunto => $documentos) {
                $assunto = AssuntoRequerimento::where('setor_id', $modelo->id)
                                              ->where('codigo', $codigoAssunto)
                                              ->first();

                if (!$assunto) {
                    $this->command->warn("Assunto código '{$codigoAssunto}' do modelo '{$identificadorModelo}' não encontrado. Pulando...");
                    continue;
                }

                // Remove documentos antigos do assunto (re-seed idempotente)
                $assunto->documentos()->delete();

                foreach ($documentos as $doc) {
                    DocumentoAssunto::create([
                        'assunto_requerimento_id' => $assunto->id,
                        'nome'                    => $doc['nome'],
                        'descricao'               => $doc['descricao'] ?? null,
                        'obrigatorio'             => true,
                        'tipos_aceitos'           => $doc['tipos_aceitos'] ?? 'pdf,jpg,jpeg,png',
                    ]);
                }

                $this->command->info("✓ {$identificadorModelo}/{$codigoAssunto}: " . count($documentos) . " documento(s) cadastrado(s).");
            }
        }

        $this->command->info('Seeder concluído com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\InformacoesAlunoMail;
use App\Models\Requerimento;
use App\Models\Setor;
use App\Services\DocumentoRequerimentoService;
use App\Services\RequerimentoEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnvioEmailController extends Controller
{
    /**
     * Envia o e-mail com as informações do aluno para seu e-mail pessoal e o setor selecionado.
     */
     public function enviar(Request $request)
     {
         $request->validate([
            'setor'                => 'nullable|string',
            'setor_id'             => 'nullable',
            'objeto'               => 'nullable|string|max:255',
            'objetoDoRequerimento' => 'nullable|string|max:255',
            'objeto_outro'         => 'nullable|string|max:255',
            'motivo'               => 'required|string|max:3000',
            'mensagem'             => 'nullable|string|max:3000',
            'email_pessoal'        => 'nullable|email|max:255',
            'telefone'             => 'nullable|string|max:30',
            'rua'                  => 'nullable|string|max:255',
            'numero'               => 'nullable|string|max:20',
            'bairro'               => 'nullable|string|max:255',
            'cidade'               => 'nullable|string|max:255',
            'estado'               => 'nullable|string|max:2',
            'cep'                  => 'nullable|string|max:10',
            'endereco'             => 'nullable|string|max:255',
            'email_adicional'      => 'nullable|email',
            'arquivos.*'           => 'nullable|file|max:51200', // 50MB por arquivo complementar
            'documentos.*'         => 'nullable|file|max:51200', // 50MB por documento obrigatório
            'documentos_responsavel.*' => 'nullable|file|max:51200',
        ]);

        $setorParam = $request->input('setor_id') ?? $request->input('setor');
        $setor = null;
        if (is_numeric($setorParam)) {
            $setor = Setor::find($setorParam);
        }
        if (!$setor && !empty($setorParam)) {
            $setor = Setor::where('setor_sigla', $setorParam)->first();
        }
        if (!$setor) {
            $setor = Setor::where('ativo', true)->first();
        }

        if (!$setor) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => ['setor' => ['O setor selecionado é inválido.']]], 422);
            }
            return back()->withErrors(['setor' => 'O setor selecionado é inválido.']);
        }

        $aluno = auth()->user();

        // 1. Atualiza campos cadastrais do usuário
        $dadosUsuario = [];
        if ($request->filled('email_pessoal')) {
            $aluno->email_pessoal = $request->input('email_pessoal');
            $dadosUsuario['email_pessoal'] = $aluno->email_pessoal;
        }
        if ($request->filled('telefone')) {
            $aluno->telefone = $request->input('telefone');
            $dadosUsuario['telefone'] = $aluno->telefone;
        }

        if (!empty($dadosUsuario)) {
            try {
                $aluno->update($dadosUsuario);
            } catch (\Throwable $e) {
                logger()->info('Não foi possível persistir dados cadastrais do usuário: ' . $e->getMessage());
            }
        }

        // 2. Atualiza dados de endereço na tabela 'enderecos'
        $dadosEndereco = [];
        foreach (['rua', 'numero', 'bairro', 'cidade', 'estado', 'cep'] as $campo) {
            if ($request->filled($campo)) {
                $dadosEndereco[$campo] = $request->input($campo);
            }
        }

        // Se veio string única 'endereco' no formulário legado e nenhum campo individual foi preenchido
        if (empty($dadosEndereco) && $request->filled('endereco')) {
            $parsed = \App\Services\Suap\EnderecoScraper::parse($request->input('endereco'));
            if ($parsed) {
                $dadosEndereco = $parsed;
            } else {
                $dadosEndereco['rua'] = $request->input('endereco');
            }
        }

        if (!empty($dadosEndereco)) {
            try {
                $aluno->endereco()->updateOrCreate([], $dadosEndereco);
                $aluno->load('endereco');
            } catch (\Throwable $e) {
                logger()->info('Não foi possível persistir dados de endereço: ' . $e->getMessage());
            }
        }

        $objeto = !empty($request->input('objeto_outro'))
            ? 'Outros: ' . $request->input('objeto_outro')
            : ($request->input('objetoDoRequerimento') ?? $request->input('objeto', 'Requerimento Geral'));

        $motivo = !empty($request->input('motivo'))
            ? $request->input('motivo')
            : (!empty($request->input('mensagem')) ? $request->input('mensagem') : 'Solicitação de ' . $objeto);

        $servicoEmail = app(RequerimentoEmailService::class);
        $destinatarios = $servicoEmail->resolverDestinatarios(
            $setor,
            $aluno,
            $request->input('email_adicional')
        );

        if ($destinatarios === null) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => ['geral' => ['Nenhum e-mail de destino válido foi encontrado.']]], 422);
            }
            return back()->withErrors(['geral' => 'Nenhum e-mail de destino válido foi encontrado.']);
        }

        // 2. Validação de documentos obrigatórios e coleta de arquivos
        $assunto = null;
        if (empty($request->input('objeto_outro'))) {
            $objetoTexto = $request->input('objetoDoRequerimento') ?? $request->input('objeto');
            $assunto = \App\Models\AssuntoRequerimento::where('descricao', $objetoTexto)->first();

            if ($assunto) {
                $documentosAssunto = $assunto->documentos()->get();
                $documentosEnviados = $request->file('documentos', []);
                $documentosResponsavel = $request->file('documentos_responsavel', []);
                $alunoMenor = $aluno->isMenorDeIdade();

                $errosAnexos = [];
                foreach ($documentosAssunto as $doc) {
                    $arquivoDoc = $documentosEnviados[$doc->id] ?? null;

                    if ($doc->obrigatorio && !$this->temArquivoValido($arquivoDoc)) {
                        $errosAnexos[] = "O documento '{$doc->nome}' é obrigatório para a solicitação de '{$assunto->descricao}'.";
                    }

                    if ($alunoMenor && $doc->exige_documentos_responsaveis) {
                        if (!$this->temArquivoValido($documentosResponsavel[$doc->id] ?? null)) {
                            $errosAnexos[] = "A cópia do documento '{$doc->nome}' de um responsável legal é obrigatória.";
                        }
                    }
                }

                if (!empty($errosAnexos)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['errors' => ['anexos' => $errosAnexos]], 422);
                    }
                    return back()->withErrors($errosAnexos)->withInput();
                }
            }
        }

        // Coleta todos os arquivos enviados (específicos de documentos + complementares)
        $todosArquivosInput = [
            $request->file('documentos', []),
            $request->file('documentos_responsavel', []),
            $request->file('arquivos', [])
        ];

        $arquivos = [];
        array_walk_recursive($todosArquivosInput, function ($item) use (&$arquivos) {
            if ($item instanceof \Illuminate\Http\UploadedFile && $item->isValid()) {
                $arquivos[] = $item;
            }
        });

        logger()->info('Requerimento: arquivos coletados', [
            'total'     => count($arquivos),
            'nomes'     => array_map(fn($f) => $f->getClientOriginalName(), $arquivos),
            'documentos_raw' => array_keys($request->file('documentos', [])),
        ]);

        // 3. Salva o registro no banco de dados
        $requerimento = null;
        try {
            // Tenta encontrar o assunto pelo texto selecionado
            $assunto = \App\Models\AssuntoRequerimento::where('descricao', $objeto)->first();

            $requerimento = Requerimento::create([
                'usuario_id'             => $aluno->id,
                'assunto_requerimento_id' => $assunto?->id,
                'objetoDoRequerimento'   => $objeto,
                'motivo'                 => $motivo,
                'status'                 => 'Aberto',
                'setor_id'                => $setor->id,
            ]);
            // Chama método para gerar número de protocolo;
            $this->gerarNumeroProtocolo($requerimento);

            $dominioEmail = substr(strrchr((string) config('mail.from.address'), '@') ?: '', 1);
            $dominioEmail = $dominioEmail ?: (parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost');
            $requerimento->forceFill([
                'email_message_id' => Str::uuid() . '@' . $dominioEmail,
            ])->save();

            // Salva no banco e no storage os documentos enviados vinculados ao histórico inicial
            $historicoInicial = $requerimento->historicos()->first();
            $documentosParaSalvar = $request->file('documentos', []);
            foreach ($request->file('documentos_responsavel', []) as $documentoId => $arquivoResponsavel) {
                $documentosParaSalvar["responsavel_{$documentoId}"] = $arquivoResponsavel;
            }
            $docsIniciaisSalvos = app(DocumentoRequerimentoService::class)->salvarDocumentosIniciais(
                requerimento: $requerimento,
                historico: $historicoInicial,
                documentosInput: $documentosParaSalvar,
                arquivosComplementares: $request->file('arquivos', []),
                usuario: $aluno
            );
        } catch (\Throwable $e) {
            logger()->error('Não foi possível salvar requerimento e documentos no BD/armazenamento.', [
                'exception' => $e,
            ]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Ocorreu um erro ao salvar ou processar o requerimento: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }

        $resultadoPdf = app(DocumentoRequerimentoService::class)->gerarESalvarPdfRequerimento(
            requerimento: $requerimento,
            aluno: $aluno,
            setorNome: $setor->setor_nome,
            arquivos: $arquivos,
            historico: $requerimento?->historicos()->first(),
            usuario: $aluno,
            setorChave: (string) $setor->id,
            objeto: $objeto,
            mensagem: $motivo
        );

        // Mapeia os caminhos dos arquivos não mesclados que já estão salvos no storage local
        $anexosNaoMescladosCaminhos = [];
        if (!empty($resultadoPdf['arquivos_nao_mesclados']) && !empty($docsIniciaisSalvos)) {
            $documentosPorNomeOriginal = [];
            foreach ($docsIniciaisSalvos as $docSalvo) {
                $documentosPorNomeOriginal[$docSalvo->nome_original][] = $docSalvo;
            }

            foreach ($resultadoPdf['arquivos_nao_mesclados'] as $arqNaoMesclado) {
                $nomeArq = ($arqNaoMesclado instanceof \Illuminate\Http\UploadedFile)
                    ? $arqNaoMesclado->getClientOriginalName()
                    : (is_string($arqNaoMesclado) ? basename($arqNaoMesclado) : null);

                if ($nomeArq) {
                    $docSalvo = !empty($documentosPorNomeOriginal[$nomeArq])
                        ? array_shift($documentosPorNomeOriginal[$nomeArq])
                        : null;
                    if ($docSalvo && !empty($docSalvo->caminho)) {
                        $anexosNaoMescladosCaminhos[] = $docSalvo->caminho;
                    }
                }
            }
        }

        // Envia durante a requisição para não depender de um worker de fila.
        if ($requerimento) {
            \App\Jobs\EnviarEmailRequerimentoJob::dispatchSync(
                requerimentoId: $requerimento->id,
                destinatarios: $destinatarios,
                pdfDocumentoId: $resultadoPdf['pdf']['documento_id'] ?? null,
                anexosNaoMescladosCaminhos: $anexosNaoMescladosCaminhos,
                mensagem: $motivo,
                objeto: $objeto,
                setorNome: $setor->setor_nome,
                setorChave: (string) $setor->id
            );
        }

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('sucesso', 'Requerimento enviado com sucesso!');
            return response()->json([
                'success' => true,
                'message' => 'Requerimento enviado com sucesso!',
                'redirect' => route('requerimentos.aluno.meusRequerimentos'),
            ]);
        }

        return redirect()->route('requerimentos.aluno.meusRequerimentos')->with('sucesso', 'Requerimento enviado com sucesso!');
    }

    public function gerarNumeroProtocolo(Requerimento $requerimento){
        $ano = $requerimento->created_at?->format('Y') ?? date('Y');
        $requerimento->numero_protocolo = $ano . '/' . $requerimento->id;
        $requerimento->save();
    }

    private function temArquivoValido(mixed $arquivo): bool
    {
        if ($arquivo instanceof UploadedFile) {
            return $arquivo->isValid();
        }

        if (is_array($arquivo)) {
            foreach ($arquivo as $item) {
                if ($this->temArquivoValido($item)) {
                    return true;
                }
            }
        }

        return false;
    }
}

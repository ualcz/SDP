<?php

namespace App\Http\Controllers;

use App\Mail\InformacoesAlunoMail;
use App\Models\Requerimento;
use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'motivo'               => 'nullable|string|max:3000',
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

        // 1. Montagem dos destinatários separados por papel
        // O setor vai no campo "Para:" (to) e os e-mails do aluno vão no "CC:"
        // Assim, um único e-mail é enviado com todos os destinatários visíveis na mesma mensagem.

        $emailsSetor = [];
        if (!empty($setor->email)) {
            $emailsSetor = is_array($setor->email) ? $setor->email : [$setor->email];
        }

        $emailsAluno = [];
        if (!empty($aluno->email_pessoal)) {
            $emailsAluno[] = $aluno->email_pessoal;
        }
        if (!empty($aluno->email)) {
            $emailsAluno[] = $aluno->email;
        }
        if (!empty($request->input('email_adicional'))) {
            $emailsAluno[] = $request->input('email_adicional');
        }

        // Remove duplicados e valores inválidos de cada lista
        $emailsSetor = array_values(array_unique(array_filter(array_map('trim', $emailsSetor))));
        $emailsAluno = array_values(array_unique(array_filter(array_map('trim', $emailsAluno))));

        // Remove do CC e-mails que já estão no "Para:" para evitar duplicatas
        $emailsAluno = array_values(array_diff($emailsAluno, $emailsSetor));

        if (empty($emailsSetor) && empty($emailsAluno)) {
            return back()->withErrors(['geral' => 'Nenhum e-mail de destino válido foi encontrado.']);
        }

        // Se não há e-mail de setor, usa os e-mails do aluno como destinatário principal
        $toEmails  = !empty($emailsSetor) ? $emailsSetor : $emailsAluno;
        $ccEmails  = !empty($emailsSetor) ? $emailsAluno  : [];

        // 2. Validação de documentos obrigatórios e coleta de arquivos
        $assunto = null;
        if (empty($request->input('objeto_outro'))) {
            $objetoTexto = $request->input('objetoDoRequerimento') ?? $request->input('objeto');
            $assunto = \App\Models\AssuntoRequerimento::where('descricao', $objetoTexto)->first();

            if ($assunto) {
                $docsObrigatorios = $assunto->documentosObrigatorios()->get();
                $documentosEnviados = $request->file('documentos', []);

                $errosAnexos = [];
                foreach ($docsObrigatorios as $doc) {
                    $arquivoDoc = $documentosEnviados[$doc->id] ?? null;

                    if (!$arquivoDoc || !($arquivoDoc instanceof \Illuminate\Http\UploadedFile) || !$arquivoDoc->isValid()) {
                        $errosAnexos[] = "O documento '{$doc->nome}' é obrigatório para a solicitação de '{$assunto->descricao}'.";
                    }
                }

                if (!empty($errosAnexos)) {
                    return back()->withErrors($errosAnexos)->withInput();
                }
            }
        }

        // Coleta todos os arquivos enviados (específicos de documentos + complementares)
        $todosArquivosInput = [
            $request->file('documentos', []),
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
        try {
            // Tenta encontrar o assunto pelo texto selecionado
            $assunto = \App\Models\AssuntoRequerimento::where('descricao', $objeto)->first();

            $requerimento = Requerimento::create([
                'usuario_id'             => $aluno->id,
                'assunto_requerimento_id' => $assunto?->id,
                'objetoDoRequerimento'   => $objeto,
                'motivo'                 => $motivo,
                'status'                 => 'Em Análise',
            ]);
            // Chama método para gerar número de protocolo;
            $this->gerarNumeroProtocolo($requerimento);
        } catch (\Exception $e) {
            logger()->warning('Não foi possível salvar requerimento no BD: ' . $e->getMessage());
        }

        // 4. Dispara um único e-mail com setor no "Para:" e aluno no "CC:"
        $mailable = new InformacoesAlunoMail(
            aluno: $aluno,
            setorNome: $setor->setor_nome,
            mensagem: $motivo,
            arquivos: is_array($arquivos) ? $arquivos : [$arquivos],
            objeto: $objeto,
            setorChave: (string) $setor->id
        );

        $mailer = Mail::to($toEmails);
        if (!empty($ccEmails)) {
            $mailer = $mailer->cc($ccEmails);
        }
        $mailer->send($mailable);

        return back()->with('sucesso', 'Requerimento enviado com sucesso!');
    }

    public function gerarNumeroProtocolo(Requerimento $requerimento){
        //Gera um número aleatório no formato: anoAtual/sequenciaAleatoriaDeSeisDígitos e salva no banco de dados;
        $ano = date('Y');
        $sequencia = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $requerimento->numero_protocolo = $ano . '/' . $sequencia;
        $requerimento->save();      
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\InformacoesAlunoMail;
use App\Models\Requerimento;
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
            'setor' => 'required|string',
            'objeto' => 'nullable|string|max:255',
            'objetoDoRequerimento' => 'nullable|string|max:255',
            'objeto_outro' => 'nullable|string|max:255',
            'motivo' => 'nullable|string|max:3000',
            'mensagem' => 'nullable|string|max:3000',
            'email_pessoal' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:30',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'endereco' => 'nullable|string|max:255',
            'email_adicional' => 'nullable|email',
            'arquivos.*' => 'nullable|file|max:10240', // limite de 10MB por anexo
        ]);

        $setores = config('setores.destinatarios', []);
        $chaveSetor = $request->input('setor');

        if (!isset($setores[$chaveSetor])) {
            return back()->withErrors(['setor' => 'O setor selecionado é inválido.']);
        }

        $setor = $setores[$chaveSetor];
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
        if (!empty($setor['email'])) {
            $emailsSetor = is_array($setor['email']) ? $setor['email'] : [$setor['email']];
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

        // 2. Coleta os arquivos enviados no formulário
        $arquivos = $request->file('arquivos', []);

        // 3. Salva o registro no banco de dados
        try {
            // Tenta encontrar o assunto pelo texto selecionado
            $assunto = \App\Models\AssuntoRequerimento::where('descricao', $objeto)->first();

            Requerimento::create([
                'usuario_id'             => $aluno->id,
                'assunto_requerimento_id' => $assunto?->id,
                'objetoDoRequerimento'   => $objeto,
                'motivo'                 => $motivo,
                'status'                 => 'Em Análise',
            ]);
        } catch (\Exception $e) {
            logger()->warning('Não foi possível salvar requerimento no BD: ' . $e->getMessage());
        }

        // 4. Dispara um único e-mail com setor no "Para:" e aluno no "CC:"
        $mailable = new InformacoesAlunoMail(
            aluno: $aluno,
            setorNome: $setor['nome'],
            mensagem: $motivo,
            arquivos: is_array($arquivos) ? $arquivos : [$arquivos],
            objeto: $objeto,
            setorChave: $chaveSetor
        );

        $mailer = Mail::to($toEmails);
        if (!empty($ccEmails)) {
            $mailer = $mailer->cc($ccEmails);
        }
        $mailer->send($mailable);

        return back()->with('sucesso', 'Requerimento enviado com sucesso!');
    }
}

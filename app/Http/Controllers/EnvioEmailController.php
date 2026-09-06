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
            'mensagem' => 'nullable|string|max:3000',
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
        $objeto = !empty($request->input('objeto_outro')) 
            ? 'Outros: ' . $request->input('objeto_outro') 
            : $request->input('objeto', 'Requerimento Geral');

        // 1. Montagem da lista de destinatários
        $destinatarios = [];

        // E-mail(s) do setor selecionado (aceita string ou array de e-mails)
        if (!empty($setor['email'])) {
            if (is_array($setor['email'])) {
                $destinatarios = array_merge($destinatarios, $setor['email']);
            } else {
                $destinatarios[] = $setor['email'];
            }
        }

        // E-mail pessoal do aluno (se existir) e/ou institucional
        if (!empty($aluno->email_pessoal)) {
            $destinatarios[] = $aluno->email_pessoal;
        }
        if (!empty($aluno->email)) {
            $destinatarios[] = $aluno->email;
        }
        if (!empty($request->input('email_adicional'))) {
            $destinatarios[] = $request->input('email_adicional');
        }

        // Remove duplicados, nulos e espaços em branco da lista
        $destinatarios = array_values(array_unique(array_filter(array_map('trim', $destinatarios))));

        if (empty($destinatarios)) {
            return back()->withErrors(['geral' => 'Nenhum e-mail de destino válido foi encontrado.']);
        }

        // 2. Coleta os arquivos enviados no formulário
        $arquivos = $request->file('arquivos', []);

        // 3. Salva o registro no banco de dados
        try {
            Requerimento::create([
                'objetoDoRequerimento' => $objeto,
                'motivo' => $request->input('mensagem') ?? 'Solicitação de ' . $objeto,
                'situação' => 'Em Análise',
            ]);
        } catch (\Exception $e) {
            // Caso a tabela ainda não esteja migrada, continua o envio de e-mail sem quebrar a execução
            logger()->warning('Não foi possível salvar requerimento no BD: ' . $e->getMessage());
        }

        // 4. Dispara o e-mail
        Mail::to($destinatarios)->send(new InformacoesAlunoMail(
            aluno: $aluno,
            setorNome: $setor['nome'],
            mensagem: $request->input('mensagem'),
            arquivos: is_array($arquivos) ? $arquivos : [$arquivos],
            objeto: $objeto,
            setorChave: $chaveSetor
        ));

        return back()->with('sucesso', 'Requerimento enviado com sucesso!');
    }
}

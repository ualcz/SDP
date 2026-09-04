<?php

namespace App\Http\Controllers;

use App\Mail\InformacoesAlunoMail;
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

        if (empty($destinatarios)) {
            return back()->withErrors(['geral' => 'Nenhum e-mail de destino válido foi encontrado.']);
        }

        // 2. Coleta os arquivos enviados no formulário
        $arquivos = $request->file('arquivos', []);

        // 3. Dispara o e-mail
        Mail::to($destinatarios)->send(new InformacoesAlunoMail(
            aluno: $aluno,
            setorNome: $setor['nome'],
            mensagem: $request->input('mensagem'),
            arquivos: is_array($arquivos) ? $arquivos : [$arquivos]
        ));

        return back()->with('sucesso', 'E-mail enviado com sucesso para: ' . implode(', ', $destinatarios));
    }
}

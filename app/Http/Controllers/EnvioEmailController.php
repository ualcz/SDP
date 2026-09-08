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

        // Armazena os dados na tabela 'requerimentos' antes de enviar via e-mail;
        $data = $request->all();
        $data['usuario_id'] = auth()->id();
        $requerimento = Requerimento::create($data);
        $requerimento->save();

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
                'usuario_id' => $aluno->id,
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

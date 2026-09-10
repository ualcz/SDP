<?php

namespace App\Http\Controllers;

use App\Models\AssuntoRequerimento;
use App\Models\Setor;
use Illuminate\Http\Request;

class AdminModeloController extends Controller
{
    public function index()
    {
        $modelos = Setor::withCount(['assuntos', 'assuntosAtivos'])->get();

        return view('admin.modelos.index', compact('modelos'));
    }

    public function edit($id)
    {
        $modelo = Setor::with(['assuntos'])->findOrFail($id);

        return view('admin.modelos.edit', compact('modelo'));
    }

    public function update(Request $request, $id)
    {
        $modelo = Setor::findOrFail($id);

        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'setor_sigla' => 'required|string|max:50',
            'setor_nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'processo_prefixo' => 'nullable|string|max:20',
            'rodape_contato' => 'nullable|string|max:255',
            'observacoes_texto' => 'nullable|string',
            'ativo' => 'nullable|boolean',
        ]);

        $observacoes = [];
        if (!empty($dados['observacoes_texto'])) {
            $linhas = preg_split('/\r\n|\r|\n/', $dados['observacoes_texto']);
            foreach ($linhas as $linha) {
                $linha = trim($linha);
                if ($linha !== '') {
                    $observacoes[] = $linha;
                }
            }
        }

        $modelo->update([
            'titulo' => $dados['titulo'],
            'setor_sigla' => $dados['setor_sigla'],
            'setor_nome' => $dados['setor_nome'],
            'email' => $dados['email'] ?? null,
            'processo_prefixo' => $dados['processo_prefixo'] ?: '23720',
            'rodape_contato' => $dados['rodape_contato'] ?? null,
            'observacoes' => $observacoes,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.modelos.edit', $modelo->id)
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function storeAssunto(Request $request, $setorId)
    {
        $setor = Setor::findOrFail($setorId);

        $dados = $request->validate([
            'codigo' => 'nullable|string|max:20',
            'descricao' => 'required|string|max:255',
            'observacao' => 'nullable|string|max:500',
            'ordem' => 'nullable|integer',
        ]);

        $maxOrdem = $setor->assuntos()->max('ordem') ?? 0;

        AssuntoRequerimento::create([
            'setor_id' => $setor->id,
            'codigo' => $dados['codigo'] ?? null,
            'descricao' => $dados['descricao'],
            'observacao' => $dados['observacao'] ?? null,
            'ordem' => $dados['ordem'] ?? ($maxOrdem + 1),
            'ativo' => true,
        ]);

        return redirect()->route('admin.modelos.edit', $setor->id)
            ->with('success', 'Assunto adicionado com sucesso!');
    }

    public function updateAssunto(Request $request, $assuntoId)
    {
        $assunto = AssuntoRequerimento::findOrFail($assuntoId);

        $dados = $request->validate([
            'codigo' => 'nullable|string|max:20',
            'descricao' => 'required|string|max:255',
            'observacao' => 'nullable|string|max:500',
            'ordem' => 'required|integer',
            'ativo' => 'nullable|boolean',
        ]);

        $assunto->update([
            'codigo' => $dados['codigo'] ?? null,
            'descricao' => $dados['descricao'],
            'observacao' => $dados['observacao'] ?? null,
            'ordem' => $dados['ordem'],
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.modelos.edit', $assunto->setor_id)
            ->with('success', 'Assunto atualizado com sucesso!');
    }

    public function destroyAssunto($assuntoId)
    {
        $assunto = AssuntoRequerimento::findOrFail($assuntoId);
        $setorId = $assunto->setor_id;
        $assunto->delete();

        return redirect()->route('admin.modelos.edit', $setorId)
            ->with('success', 'Assunto removido com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AssuntoRequerimento;
use App\Models\DocumentoAssunto;
use App\Models\Setor;
use Illuminate\Http\Request;

class AdminSetorController extends Controller
{
    public function index()
    {
        $setores = Setor::withCount(['assuntos', 'assuntosAtivos'])->get();
        $modelos = $setores; // compatibilidade com as views

        return view('admin.setor.index', compact('setores', 'modelos'));
    }

    public function create()
    {
        return view('admin.setor.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo'           => 'required|string|max:255',
            'setor_sigla'      => 'required|string|max:50',
            'setor_nome'       => 'required|string|max:255',
            'email'            => 'nullable|email|max:255',
            'processo_prefixo' => 'nullable|string|max:20',
            'rodape_contato'   => 'nullable|string|max:255',
            'ativo'            => 'nullable|boolean',
        ]);

        $setor = Setor::create([
            'titulo'           => $dados['titulo'],
            'setor_sigla'      => $dados['setor_sigla'],
            'setor_nome'       => $dados['setor_nome'],
            'email'            => $dados['email'] ?? null,
            'processo_prefixo' => $dados['processo_prefixo'] ?: '23720',
            'rodape_contato'   => $dados['rodape_contato'] ?? null,
            'ativo'            => $request->has('ativo'),
        ]);

        return redirect()->route('admin.setores.edit', $setor->id)
            ->with('success', 'Setor criado com sucesso!');
    }

    public function edit($id)
    {
        $setor = Setor::with(['assuntos.documentos'])->findOrFail($id);
        $modelo = $setor; // compatibilidade com as views

        return view('admin.setor.edit', compact('setor', 'modelo'));
    }

    public function update(Request $request, $id)
    {
        $setor = Setor::findOrFail($id);

        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'setor_sigla' => 'required|string|max:50',
            'setor_nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'processo_prefixo' => 'nullable|string|max:20',
            'rodape_contato' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
        ]);

        $setor->update([
            'titulo' => $dados['titulo'],
            'setor_sigla' => $dados['setor_sigla'],
            'setor_nome' => $dados['setor_nome'],
            'email' => $dados['email'] ?? null,
            'processo_prefixo' => $dados['processo_prefixo'] ?: '23720',
            'rodape_contato' => $dados['rodape_contato'] ?? null,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.setores.edit', $setor->id)
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

        return redirect()->route('admin.setores.edit', $setor->id)
            ->with('success', 'Requerimento adicionado com sucesso!');
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

        return redirect()->route('admin.setores.edit', $assunto->setor_id)
            ->with('success', 'Requerimento atualizado com sucesso!');
    }

    public function destroyAssunto($assuntoId)
    {
        $assunto = AssuntoRequerimento::findOrFail($assuntoId);
        $setorId = $assunto->setor_id;
        $assunto->delete();

        return redirect()->route('admin.setores.edit', $setorId)
            ->with('success', 'Requerimento removido com sucesso!');
    }

    public function storeDocumento(Request $request, $assuntoId)
    {
        $assunto = AssuntoRequerimento::findOrFail($assuntoId);

        $dados = $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'nullable|string|max:500',
            'obrigatorio'   => 'nullable|boolean',
            'tipos_aceitos' => 'nullable|string|max:100',
        ]);

        DocumentoAssunto::create([
            'assunto_requerimento_id' => $assunto->id,
            'nome'                    => $dados['nome'],
            'descricao'               => $dados['descricao'] ?? null,
            'obrigatorio'             => $request->has('obrigatorio'),
            'tipos_aceitos'           => $dados['tipos_aceitos'] ?: 'pdf,jpg,jpeg,png',
        ]);

        return redirect()->route('admin.setores.edit', $assunto->setor_id)
            ->with('success', 'Documento anexado ao requerimento com sucesso!');
    }

    public function updateDocumento(Request $request, $documentoId)
    {
        $documento = DocumentoAssunto::with('assunto')->findOrFail($documentoId);

        $dados = $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'nullable|string|max:500',
            'obrigatorio'   => 'nullable|boolean',
            'tipos_aceitos' => 'nullable|string|max:100',
        ]);

        $documento->update([
            'nome'          => $dados['nome'],
            'descricao'     => $dados['descricao'] ?? null,
            'obrigatorio'   => $request->has('obrigatorio'),
            'tipos_aceitos' => $dados['tipos_aceitos'] ?: 'pdf,jpg,jpeg,png',
        ]);

        $setorId = $documento->assunto?->setor_id;

        return redirect()->route('admin.setores.edit', $setorId)
            ->with('success', 'Documento atualizado com sucesso!');
    }

    public function destroyDocumento($documentoId)
    {
        $documento = DocumentoAssunto::with('assunto')->findOrFail($documentoId);
        $setorId = $documento->assunto?->setor_id;
        $documento->delete();

        return redirect()->route('admin.setores.edit', $setorId)
            ->with('success', 'Documento removido com sucesso!');
    }
}

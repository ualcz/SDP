<?php

namespace App\Http\Controllers;

use App\Models\AssuntoRequerimento;
use App\Models\DocumentoAssunto;
use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'ativo'            => 'nullable|boolean',
        ]);

        $setor = Setor::create([
            'titulo'           => $dados['titulo'],
            'setor_sigla'      => $dados['setor_sigla'],
            'setor_nome'       => $dados['setor_nome'],
            'email'            => $dados['email'] ?? null,
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
            'ativo' => 'nullable|boolean',
        ]);

        $setor->update([
            'titulo' => $dados['titulo'],
            'setor_sigla' => $dados['setor_sigla'],
            'setor_nome' => $dados['setor_nome'],
            'email' => $dados['email'] ?? null,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.setores.edit', $setor->id)
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function createAssunto($setorId)
    {
        $setor = Setor::findOrFail($setorId);
        $modelo = $setor;

        return view('admin.setor.criar-assunto', compact('setor', 'modelo'));
    }

    public function storeAssunto(Request $request, $setorId)
    {
        $setor = Setor::findOrFail($setorId);

        $dados = $request->validate([
            'descricao'                => 'required|string|max:255',
            'observacao'               => 'nullable|string|max:500',
            'ordem'                    => 'nullable|integer',
            'documentos'               => 'nullable|array',
            'documentos.*.nome'        => 'nullable|string|max:255',
            'documentos.*.descricao'   => 'nullable|string|max:500',
            'documentos.*.obrigatorio' => 'nullable',
        ]);

        $maxOrdem = $setor->assuntos()->max('ordem') ?? 0;

        DB::transaction(function () use ($setor, $dados, $maxOrdem) {
            $assunto = AssuntoRequerimento::create([
                'setor_id'   => $setor->id,
                'descricao'  => trim($dados['descricao']),
                'observacao' => !empty($dados['observacao']) ? trim($dados['observacao']) : null,
                'ordem'      => $dados['ordem'] ?? ($maxOrdem + 1),
                'ativo'      => true,
            ]);

            if (!empty($dados['documentos']) && is_array($dados['documentos'])) {
                foreach ($dados['documentos'] as $docData) {
                    $nome = isset($docData['nome']) ? trim($docData['nome']) : '';
                    if ($nome !== '') {
                        DocumentoAssunto::create([
                            'assunto_requerimento_id' => $assunto->id,
                            'nome'                    => $nome,
                            'descricao'               => !empty($docData['descricao']) ? trim($docData['descricao']) : null,
                            'obrigatorio'             => !empty($docData['obrigatorio']),
                            'tipos_aceitos'           => 'pdf,jpg,jpeg,png',
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.setores.edit', $setor->id)
            ->with('success', 'Requerimento adicionado com sucesso!');
    }

    public function updateAssunto(Request $request, $assuntoId)
    {
        $assunto = AssuntoRequerimento::with('documentos')->findOrFail($assuntoId);

        $dados = $request->validate([
            'descricao'                     => 'required|string|max:255',
            'observacao'                    => 'nullable|string|max:500',
            'ordem'                         => 'required|integer',
            'ativo'                         => 'nullable|boolean',
            'documentos'                    => 'nullable|array',
            'documentos.*.nome'             => 'required|string|max:255',
            'documentos.*.descricao'        => 'nullable|string|max:500',
            'documentos.*.obrigatorio'      => 'nullable',
            'novos_documentos'              => 'nullable|array',
            'novos_documentos.*.nome'       => 'nullable|string|max:255',
            'novos_documentos.*.descricao'  => 'nullable|string|max:500',
            'novos_documentos.*.obrigatorio'=> 'nullable',
            'novo_documento.nome'           => 'nullable|string|max:255',
            'novo_documento.descricao'      => 'nullable|string|max:500',
            'novo_documento.obrigatorio'    => 'nullable',
        ]);

        DB::transaction(function () use ($assunto, $request, $dados) {
            // Atualiza o requerimento (assunto)
            $assunto->update([
                'descricao'  => $dados['descricao'],
                'observacao' => $dados['observacao'] ?? null,
                'ordem'      => $dados['ordem'],
                'ativo'      => $request->has('ativo'),
            ]);

            // Atualiza todos os documentos existentes vinculados
            if (!empty($dados['documentos']) && is_array($dados['documentos'])) {
                foreach ($dados['documentos'] as $docId => $docData) {
                    $doc = $assunto->documentos->firstWhere('id', $docId);
                    if ($doc && !empty($docData['nome'])) {
                        $doc->update([
                            'nome'        => $docData['nome'],
                            'descricao'   => $docData['descricao'] ?? null,
                            'obrigatorio' => !empty($docData['obrigatorio']),
                        ]);
                    }
                }
            }

            // Salva múltiplos novos documentos criados durante a edição
            if (!empty($dados['novos_documentos']) && is_array($dados['novos_documentos'])) {
                foreach ($dados['novos_documentos'] as $novoDoc) {
                    $nome = isset($novoDoc['nome']) ? trim($novoDoc['nome']) : '';
                    if ($nome !== '') {
                        DocumentoAssunto::create([
                            'assunto_requerimento_id' => $assunto->id,
                            'nome'                    => $nome,
                            'descricao'               => !empty($novoDoc['descricao']) ? trim($novoDoc['descricao']) : null,
                            'obrigatorio'             => !empty($novoDoc['obrigatorio']),
                            'tipos_aceitos'           => 'pdf,jpg,jpeg,png',
                        ]);
                    }
                }
            }

            // Se preenchido novo documento único (retrocompatibilidade)
            if (!empty($dados['novo_documento']['nome'])) {
                DocumentoAssunto::create([
                    'assunto_requerimento_id' => $assunto->id,
                    'nome'                    => trim($dados['novo_documento']['nome']),
                    'descricao'               => $dados['novo_documento']['descricao'] ?? null,
                    'obrigatorio'             => !empty($dados['novo_documento']['obrigatorio']),
                    'tipos_aceitos'           => 'pdf,jpg,jpeg,png',
                ]);
            }
        });

        return redirect()->route('admin.setores.edit', $assunto->setor_id)
            ->with('success', 'Requerimento e alterações salvas com sucesso!');
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
            'tipos_aceitos'           => 'pdf,jpg,jpeg,png',
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

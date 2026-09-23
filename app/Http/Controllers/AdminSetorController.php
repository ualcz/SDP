<?php

namespace App\Http\Controllers;

use App\Models\AssuntoRequerimento;
use App\Models\DocumentoAssunto;
use App\Models\Setor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSetorController extends Controller
{
    private function autorizarSetor(int $setorId): void
    {
        $usuario = request()->user();

        if ($usuario?->role !== 'admin' && !$usuario?->ehResponsavelDoSetor($setorId)) {
            abort(403, 'Você não possui permissão para editar este setor.');
        }
    }

    public function index()
    {
        $setores = Setor::withCount(['assuntos', 'assuntosAtivos'])->get();
        $modelos = $setores; // compatibilidade com as views

        return view('admin.setor.index', compact('setores', 'modelos'));
    }

    public function create()
    {

        $usuarios = Usuario::where('role', '!=', 'aluno')
        ->orderBy('nome')
        ->get();

        return view('admin.setor.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo'         => 'required|string|max:255',
            'setor_sigla'    => 'required|string|max:50',
            'setor_nome'     => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'ativo'          => 'nullable|boolean',
            'responsaveis'   => 'nullable|array',
            'responsaveis.*' => 'exists:usuarios,id',
        ]);

        $setor = Setor::create([
            'titulo'      => $dados['titulo'],
            'setor_sigla' => $dados['setor_sigla'],
            'setor_nome'  => $dados['setor_nome'],
            'email'       => $dados['email'] ?? null,
            'ativo'       => $request->has('ativo'),
        ]);


        if ($request->has('responsaveis')) {
            $setor->responsaveis()->sync($request->input('responsaveis'));
        }

        return redirect()->route('admin.setores.edit', $setor->id)
            ->with('success', 'Setor criado com sucesso!');
    }

    public function edit($id)
    {
        $this->autorizarSetor((int) $id);

        // Carrega o setor junto com os assuntos, documentos e os responsáveis já vinculados
        $setor = Setor::with(['assuntos.documentos', 'responsaveis'])->findOrFail($id);
        $modelo = $setor;

        // Busca a lista de todos os usuários para o formulário de seleção
        $usuarios = Usuario::where('role', '!=', 'aluno')
        ->orderBy('nome')
        ->get();

        return view('admin.setor.edit', compact('setor', 'modelo', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $this->autorizarSetor((int) $id);

        $setor = Setor::findOrFail($id);

        $dados = $request->validate([
            'titulo'         => 'required|string|max:255',
            'setor_sigla'    => 'required|string|max:50',
            'setor_nome'     => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'ativo'          => 'nullable|boolean',
            'responsaveis'   => 'nullable|array',
            'responsaveis.*' => 'exists:usuarios,id',
        ]);

        $dadosAtualizados = [
            'titulo'      => $dados['titulo'],
            'setor_sigla' => $dados['setor_sigla'],
            'setor_nome'  => $dados['setor_nome'],
            'email'       => $dados['email'] ?? null,
        ];

        if ($request->user()->role === 'admin') {
            $dadosAtualizados['ativo'] = $request->has('ativo');
        }

        $setor->update($dadosAtualizados);

        // Apenas administradores podem alterar os responsáveis do setor.
        if ($request->user()->role === 'admin') {
            $setor->responsaveis()->sync($request->input('responsaveis', []));
        }

        return redirect()->route('admin.setores.edit', $setor->id)
            ->with('success', 'Setor atualizado com sucesso!');
    }

    public function createAssunto($setorId)
    {
        $this->autorizarSetor((int) $setorId);

        $setor = Setor::findOrFail($setorId);
        $modelo = $setor;

        return view('admin.setor.criar-assunto', compact('setor', 'modelo'));
    }

    public function storeAssunto(Request $request, $setorId)
    {
        $this->autorizarSetor((int) $setorId);

        $setor = Setor::findOrFail($setorId);

        $dados = $request->validate([
            'descricao'                => 'required|string|max:255',
            'observacao'               => 'nullable|string|max:500',
            'ordem'                    => 'nullable|integer',
            'curso_acesso'             => 'required|string|max:10',
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
                'curso_acesso' => $dados['curso_acesso'],
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
        $this->autorizarSetor((int) $assunto->setor_id);

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
            $assunto->update([
                'descricao'  => $dados['descricao'],
                'observacao' => $dados['observacao'] ?? null,
                'ordem'      => $dados['ordem'],
                'ativo'      => $request->has('ativo'),
            ]);

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
        $this->autorizarSetor((int) $assunto->setor_id);

        $setorId = $assunto->setor_id;
        $assunto->delete();

        return redirect()->route('admin.setores.edit', $setorId)
            ->with('success', 'Requerimento removido com sucesso!');
    }

    public function storeDocumento(Request $request, $assuntoId)
    {
        $assunto = AssuntoRequerimento::findOrFail($assuntoId);
        $this->autorizarSetor((int) $assunto->setor_id);

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
        $this->autorizarSetor((int) $documento->assunto->setor_id);

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
        $this->autorizarSetor((int) $documento->assunto->setor_id);

        $setorId = $documento->assunto?->setor_id;
        $documento->delete();

        return redirect()->route('admin.setores.edit', $setorId)
            ->with('success', 'Documento removido com sucesso!');
    }
}

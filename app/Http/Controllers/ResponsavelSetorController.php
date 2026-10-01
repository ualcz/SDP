<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use App\Models\Requerimento;
use App\Models\HistoricoRequerimento;
use App\Services\DocumentoRequerimentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsavelSetorController extends Controller
{
    private function autorizarSetor(Setor $setor): void
    {
        if (!Auth::user()->ehResponsavelDoSetor($setor->id)) {
            abort(403, 'Você não possui permissão para acessar este setor.');
        }
    }

    public function index(Request $request, $id)
    {
        $setor = Setor::findOrFail($id);

        $this->autorizarSetor($setor);

        $baseQuery = Requerimento::with(['usuario', 'assunto.setor'])
            ->where('setor_id', $setor->id);

        $totalAnalise = (clone $baseQuery)
            ->where('status', 'Em Análise')
            ->count();

        $totalConcluidos = (clone $baseQuery)
            ->where('status', 'Concluído')
            ->count();

        $totalIndeferidos = (clone $baseQuery)
            ->where('status', 'Indeferido')
            ->count();

        $totalAberto = (clone $baseQuery)
            ->where('status', 'Aberto')
            ->count();

        $query = (clone $baseQuery)->latest();

        if ($request->filled('aluno')) {
            $aluno = trim($request->input('aluno'));
            $query->whereHas('usuario', function ($q) use ($aluno) {
                $q->where('nome', 'like', "%{$aluno}%")
                  ->orWhere('matricula', 'like', "%{$aluno}%");
            });
        }

        if ($request->filled('turma')) {
            $turma = trim($request->input('turma'));
            $query->whereHas('usuario', function ($q) use ($turma) {
                $q->where('turma_codigo', 'like', "%{$turma}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->input('data_inicio'));
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->input('data_fim'));
        }

        $requerimentos = $query->paginate(15)->appends($request->query());

        return view('setor.responsavel.dashboard', compact(
            'setor',
            'totalAnalise',
            'totalConcluidos',
            'totalIndeferidos',
            'totalAberto',
            'requerimentos'
        ));
    }

    public function show(Setor $setor, Requerimento $requerimento)
    {
        $this->autorizarSetor($setor);

        if ((int) $requerimento->setor_id !== (int) $setor->id) {
            abort(404);
        }

        // Carrega o usuário, endereço, assunto e os históricos (linha do tempo com documentos)
        $requerimento->load(['usuario.endereco', 'assunto', 'historicos.usuario', 'historicos.documentos']);

        return view('setor.requerimentos.show', compact('setor', 'requerimento'));
    }

    public function atualizarStatus(Request $request, Setor $setor, Requerimento $requerimento)
    {
        $this->autorizarSetor($setor);

        if ((int) $requerimento->setor_id !== (int) $setor->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status'     => 'required|in:Em Análise,Indeferido,Concluído',
            'observacao' => 'required_if:status,Indeferido|nullable|string',
            'solicita_novo_documento' => 'nullable|boolean',
            'nome_documento_solicitado' => 'required_if:solicita_novo_documento,1|nullable|string',
            'arquivos'   => 'nullable|array',
            'arquivos.*' => 'nullable|file|max:51200',
        ]);

        $solicitaNovoDocumento = $validated['status'] === 'Indeferido'
            || $request->boolean('solicita_novo_documento');
        $request->merge([
            'solicita_novo_documento' => $solicitaNovoDocumento,
            'nome_documento_solicitado' => $solicitaNovoDocumento
                ? ($validated['nome_documento_solicitado'] ?? 'Documento indeferido')
                : null,
        ]);

        $requerimento->update([
            'status' => $validated['status'],
        ]);

        $novoHistorico = HistoricoRequerimento::create([
            'requerimento_id'           => $requerimento->id,
            'user_id'                   => Auth::id() ?? $requerimento->usuario_id,
            'status'                    => $requerimento->status,
            'observacao'                => $validated['observacao'] ?? 'Despacho registrado pelo setor.',
            'solicita_novo_documento'   => $solicitaNovoDocumento,
            'nome_documento_solicitado' => $solicitaNovoDocumento ? ($validated['nome_documento_solicitado'] ?? 'Documento indeferido') : null,
        ]);

        // Salva arquivos anexados pelo servidor vinculados ao histórico
        $arquivos = $request->file('arquivos', []);
        if (!empty($arquivos) && $novoHistorico) {
            app(DocumentoRequerimentoService::class)->salvarArquivos(
                requerimento: $requerimento,
                historico: $novoHistorico,
                arquivos: is_array($arquivos) ? $arquivos : [$arquivos],
                usuario: auth()->user(),
                titulo: 'Despacho / Documento do Setor'
            );
        }

        // Envia e-mail de notificação para o aluno (com cópia para o setor)
        $requerimento->notificarPartes(
            mensagem: $validated['observacao'] ?? '',
            remetente: 'setor',
            arquivos: is_array($arquivos) ? $arquivos : [],
            solicitaNovoDocumento: $solicitaNovoDocumento
        );

        activity()
            ->performedOn($requerimento)
            ->causedBy(auth()->user())
            ->withProperties([
                'status' => $validated['status'],
                'setor' => $setor->setor_sigla,
            ])
            ->log('Status do requerimento atualizado');

        return redirect()
            ->back()
            ->with('success', 'Status do requerimento atualizado para "' . $requerimento->status . '" com sucesso!');
    }
}

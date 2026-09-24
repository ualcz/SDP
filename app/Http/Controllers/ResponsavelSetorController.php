<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use App\Models\Requerimento;
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

        $requerimentos = Requerimento::where('setor_id', $setor->id);

        $totalAnalise = (clone $requerimentos)
            ->where('status', 'Em Análise')
            ->count();

        $totalConcluidos = (clone $requerimentos)
            ->where('status', 'Concluído')
            ->count();

        $totalIndeferidos = (clone $requerimentos)
            ->where('status', 'Indeferido')
            ->count();

        $totalAberto = (clone $requerimentos)
            ->where('status', 'Aberto')
            ->count();

        return view('setor.responsavel.dashboard', compact(
            'setor',
            'totalAnalise',
            'totalConcluidos',
            'totalIndeferidos',
            'totalAberto'
        ));
    }

    public function porStatus(Request $request, $id, string $status)
    {
        $setor = Setor::findOrFail($id);

        $this->autorizarSetor($setor);

        $statusMap = [
            'todos'      => null,
            'aberto'     => 'Aberto',
            'analise'    => 'Em Análise',
            'indeferido' => 'Indeferido',
            'concluido'  => 'Concluído',
        ];

        $statusBanco = $statusMap[$status] ?? ucwords(str_replace('-', ' ', $status));

        // Instancia a Query base
        $query = Requerimento::with('usuario')->where('setor_id', $setor->id);

        // 1. Filtro por Status (se aplicável)
        if ($statusBanco && $status !== 'todos') {
            $query->where('status', $statusBanco);
        }

        // 2. Filtro por Busca Textual (Protocolo, Nome ou Matrícula)
        if ($request->filled('busca')) {
            $termo = $request->input('busca');
            $query->where(function ($q) use ($termo) {
                $q->where('numero_protocolo', 'like', "%{$termo}%")
                  ->orWhereHas('usuario', function ($qUser) use ($termo) {
                      $qUser->where('nome', 'like', "%{$termo}%")
                            ->orWhere('matricula', 'like', "%{$termo}%");
                  });
            });
        }

        // 3. Filtro por Período de Datas
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->input('data_inicio'));
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->input('data_fim'));
        }

        $requerimentosAgrupados = $query->get()->groupBy('objetoDoRequerimento');

        return view('setor.requerimentos.status', [
            'setor'                  => $setor,
            'requerimentosAgrupados' => $requerimentosAgrupados,
            'statusAtual'            => $statusBanco ?? 'Todos',
        ]);
    }

    public function show(Setor $setor, Requerimento $requerimento)
    {
        $this->autorizarSetor($setor);

        if ((int) $requerimento->setor_id !== (int) $setor->id) {
            abort(404);
        }

        // Carrega o usuário, endereço, assunto e os históricos (linha do tempo)
        $requerimento->load(['usuario.endereco', 'assunto', 'historicos.usuario']);

        return view('setor.requerimentos.show', compact('setor', 'requerimento'));
    }

    public function atualizarStatus(Request $request, Setor $setor, Requerimento $requerimento)
    {
        $this->autorizarSetor($setor);

        if ((int) $requerimento->setor_id !== (int) $setor->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status'     => 'required|in:Aberto,Em Análise,Indeferido,Concluído',
            'observacao' => 'required_if:status,Indeferido|nullable|string',
            'solicita_novo_documento' => 'nullable|boolean',
            'nome_documento_solicitado' => 'required_if:solicita_novo_documento,1|nullable|string',
        ]);

        // Atualiza o status no Requerimento
        $requerimento->update([
            'status' => $validated['status'],
        ]);


        return redirect()
            ->back()
            ->with('success', 'Status do requerimento atualizado para "' . $requerimento->status . '" com sucesso!');
    }
}

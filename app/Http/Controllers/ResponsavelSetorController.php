<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use App\Models\Requerimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsavelSetorController extends Controller
{
    public function index(Request $request, $id)
    {
        $usuario = Auth::user();

        $setor = Setor::findOrFail($id);


        if (!$setor) {
            abort(403, 'Você não possui um setor vinculado.');
        }

        $requerimentos = Requerimento::where('setor_id', $setor->id);

        $totalRequerimentos = (clone $requerimentos)->count();


        $totalRecebidos = (clone $requerimentos)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

        $totalAnalise = (clone $requerimentos)
            ->where('status', 'Em Análise')
            ->count();

        $totalConcluidos = (clone $requerimentos)
            ->where('status', 'concluido')
            ->count();

        $totalAberto = (clone $requerimentos)->where('status', 'aberto')->count();

        return view('setor.responsavel.dashboard', compact(
            'setor',
            'totalRequerimentos',
            'totalAnalise',
            'totalConcluidos',
            'totalRecebidos',
            'totalAberto'
        ));
    }

   public function porStatus(Request $request, $id, string $status)
    {
        $setor = Setor::findOrFail($id);

        $statusMap = [
            'todos'     => null,
            'aberto'    => 'Aberto',
            'analise'   => 'Em Análise',
            'concluido' => 'Concluído',
        ];

        $statusBanco = $statusMap[$status] ?? ucwords(str_replace('-', ' ', $status));

        // Instancia a Query base
        $query = Requerimento::with('usuario')->where('setor_id', $setor->id);

        // 1. Filtro por Status (se aplicável)
        if ($statusBanco) {
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
}



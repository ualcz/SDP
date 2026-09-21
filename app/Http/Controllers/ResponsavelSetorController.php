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

    public function porStatus($id, string $status)
    {
        $setor = Setor::findOrFail($id);

        // Mapeamento de Slugs da URL para o valor real salvo no Banco de Dados
        $statusMap = [
            'analise'   => 'Em Análise',
            'aberto' => 'Aberto',
            'concluidos'=> 'Concluído',
        ];

        // Se o status vier pela URL em slug, converte; caso contrário, usa o parâmetro direto
        $statusBanco = $statusMap[$status] ?? ucwords(str_replace('-', ' ', $status));

        $requerimentosAgrupados = Requerimento::with('user')
            ->where('setor_id', $setor->id)
            ->where('status', $statusBanco)
            ->get()
            ->groupBy('objetoDoRequerimento');

        return view('setor.requerimentos.status', [
            'setor'                  => $setor,
            'requerimentosAgrupados' => $requerimentosAgrupados,
            'statusAtual'            => $statusBanco, // Útil para exibir no título da View!
        ]);
    }
}



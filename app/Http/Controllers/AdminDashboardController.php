<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Requerimento;
use App\Charts\qtdRequerimentoMeses;
use App\Charts\RequerimentoPorSetor;


class AdminDashboardController extends Controller
{
    public function index(Request $request, qtdRequerimentoMeses $chart, RequerimentoPorSetor $pieChart){
        $periodo = $request->get('periodo', 'mes');
        $totalRequerimentos = Requerimento::whereYear('created_at', now()->year)->count();
        $totalAnalise = Requerimento::where('status', 'Em análise')->count();
        if ($periodo === 'semana') {
            $totalRecebidos = Requerimento::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count();
        } else { 
            $totalRecebidos = Requerimento::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count();
        }
        return view('admin.dashboard', [
            'chart' => $chart->build(),
            'pieChart' => $pieChart->build(),
            'totalRequerimentos' => $totalRequerimentos,
            'totalAnalise' => $totalAnalise,
            'totalRecebidos' => $totalRecebidos,
            'periodo' => $periodo,
            'notFound' => 'Nenhum registro encontrado.'
        ]);
    }
}

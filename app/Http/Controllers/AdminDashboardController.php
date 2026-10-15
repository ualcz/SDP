<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Requerimento;
use App\Charts\QtdRequerimentoMeses;
use App\Charts\RequerimentoPorSetor;
use App\Charts\StatusSetorChart;
use App\Models\ReportRecipient;


class AdminDashboardController extends Controller
{
    public function index(Request $request, QtdRequerimentoMeses $chart, RequerimentoPorSetor $pieChart, StatusSetorChart $progressBarChart){
        $periodo = $request->get('periodo', 'mes');
        $totalRequerimentos = Requerimento::whereYear('created_at', now()->year)->count();
        $totalAnalise = Requerimento::where('status', 'Em Análise')->count();
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
            'progressBarChart' => $progressBarChart->build(),
            'totalRequerimentos' => $totalRequerimentos,
            'totalAnalise' => $totalAnalise,
            'totalRecebidos' => $totalRecebidos,
            'periodo' => $periodo,
            'notFound' => 'Nenhum registro encontrado.'
        ]);
    }

    public function updateNotifications(Request $request)
    {
        // Remove todos os registros atuais da tabela separada
        ReportRecipient::truncate();

        // Altere de 'user_ids' para 'responsaveis'
        if ($request->has('responsaveis')) {
            foreach ($request->responsaveis as $userId) {
                ReportRecipient::create([
                    'usuario_id' => $userId,
                    'is_active' => true,
                ]);
            }
        }

        return back()->with('success', 'Destinatários atualizados com sucesso!');
    }
}

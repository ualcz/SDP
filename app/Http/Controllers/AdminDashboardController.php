<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Requerimento;


class AdminDashboardController extends Controller
{
    public function index(Request $request){
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
        return view('admin.dashboard', compact('totalRequerimentos', 'totalAnalise', 'totalRecebidos', 'periodo'))->with('notFound','Nenhum registro encontrado.');
    }
}

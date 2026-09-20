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

        $totalAnalise = (clone $requerimentos)
            ->where('status', 'em_analise')
            ->count();

        $totalConcluidos = (clone $requerimentos)
            ->where('status', 'concluido')
            ->count();

        $totalRecebidos = (clone $requerimentos)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('setor.responsavel.dashboard', compact(
            'setor',
            'totalRequerimentos',
            'totalAnalise',
            'totalConcluidos',
            'totalRecebidos'
        ));
    }
}



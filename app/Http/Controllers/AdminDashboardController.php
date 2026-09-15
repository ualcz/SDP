<?php

namespace App\Http\Controllers;

use App\Models\Requerimento;
use App\Models\Setor;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Requerimento::with(['usuario', 'assunto.setor']);

        // Filtro por Aluno (nome ou matrícula)
        if ($request->filled('aluno')) {
            $aluno = trim($request->input('aluno'));
            $query->whereHas('usuario', function ($q) use ($aluno) {
                $q->where('nome', 'LIKE', "%{$aluno}%")
                  ->orWhere('matricula', 'LIKE', "%{$aluno}%");
            });
        }

        // Filtro por Setor
        if ($request->filled('setor')) {
            $setorFiltro = $request->input('setor');
            $query->where(function ($q) use ($setorFiltro) {
                $q->whereHas('assunto', function ($aq) use ($setorFiltro) {
                    $aq->where('setor_id', $setorFiltro);
                })
                ->orWhereIn('objetoDoRequerimento', function ($sub) use ($setorFiltro) {
                    $sub->select('descricao')
                        ->from('assuntos_requerimentos')
                        ->where('setor_id', $setorFiltro);
                });
            });
        }

        $requerimentos = $query->latest()->get();
        $setores = Setor::where('ativo', true)->orderBy('setor_sigla')->get();

        return view('admin.dashboard', [
            'requerimentos' => $requerimentos,
            'setores'       => $setores,
        ]);
    }
}

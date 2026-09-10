<?php

namespace App\Http\Controllers;

use App\Models\Requerimento;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $requerimentos = Requerimento::with('usuario')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', [
            'totalRequerimentos' => Requerimento::count(),
            'emAnalise' => Requerimento::where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 'Em Análise');
            })->count(),
            'deferidos'   => Requerimento::where('status', 'Deferido')->count(),
            'indeferidos' => Requerimento::where('status', 'Indeferido')->count(),
            'requerimentos' => $requerimentos,
        ]);
    }
}

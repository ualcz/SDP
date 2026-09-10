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
                $query->whereNull('situação')
                    ->orWhere('situação', 'Em análise');
            })->count(),
            'deferidos' => Requerimento::where('situação', 'Deferido')->count(),
            'indeferidos' => Requerimento::where('situação', 'Indeferido')->count(),
            'requerimentos' => $requerimentos,
        ]);
    }
}

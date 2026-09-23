<?php

namespace App\Observers;

use App\Models\Requerimento;
use App\Models\HistoricoRequerimento;
use Illuminate\Support\Facades\Auth;

class RequerimentoObserver
{
    public function created(Requerimento $requerimento): void
    {
        HistoricoRequerimento::create([
            'requerimento_id' => $requerimento->id,
            'user_id'         => Auth::id() ?? $requerimento->usuario_id,
            'status'          => $requerimento->status ?? 'aberto',
            'observacao'      => 'Requerimento cadastrado no sistema.',
        ]);
    }

    public function updated(Requerimento $requerimento): void
    {
        // Dispara APENAS se o status mudou
        if ($requerimento->isDirty('status')) {
            HistoricoRequerimento::create([
                'requerimento_id' => $requerimento->id,
                'user_id'         => Auth::id() ?? $requerimento->usuario_id,
                'status'          => $requerimento->status,
                'observacao'      => request('observacao') ?? 'Status alterado.',
            ]);
        }
    }
}

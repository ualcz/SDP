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

            // Captura os dados da requisição HTTP atual
            $solicitaDocumento = request()->boolean('solicita_novo_documento');
            $nomeDocumento    = request('nome_documento_solicitado');

            HistoricoRequerimento::create([
                'requerimento_id'           => $requerimento->id,
                'user_id'                   => Auth::id() ?? $requerimento->usuario_id,
                'status'                    => $requerimento->status,
                'observacao'                => request('observacao') ?? 'Status alterado.',
                'solicita_novo_documento'   => $solicitaDocumento,
                'nome_documento_solicitado' => $solicitaDocumento ? $nomeDocumento : null,
            ]);
        }
    }
}

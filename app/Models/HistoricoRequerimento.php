<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoRequerimento extends Model
{
    // Nome da tabela (caso não siga a convenção padrão do Laravel)
    protected $table = 'historico_requerimentos';

    // Adicione os campos que podem ser preenchidos via create() ou update()
    protected $fillable = [
        'requerimento_id',
        'user_id',
        'status',
        'observacao',
    ];

    public function requerimento()
    {
        return $this->belongsTo(Requerimento::class, 'requerimento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}

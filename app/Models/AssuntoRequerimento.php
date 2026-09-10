<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssuntoRequerimento extends Model
{
    protected $table = 'assuntos_requerimentos';

    protected $fillable = [
        'modelo_requerimento_id',
        'codigo',
        'descricao',
        'observacao',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ordem' => 'integer',
    ];

    public function modelo()
    {
        return $this->belongsTo(ModeloRequerimento::class, 'modelo_requerimento_id');
    }
}

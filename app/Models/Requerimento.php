<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    protected $fillable = [
        'usuario_id',
        'assunto_requerimento_id',
        'objetoDoRequerimento',
        'motivo',
        'status',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function user()
    {
        return $this->usuario();
    }

    /**
     * Assunto (objetivo) vinculado a este requerimento.
     */
    public function assunto()
    {
        return $this->belongsTo(AssuntoRequerimento::class, 'assunto_requerimento_id');
    }
}

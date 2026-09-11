<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Setor;
use App\Models\AssuntoRequerimento;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    protected $fillable = [
        'usuario_id',
        'assunto_requerimento_id',
        'objetoDoRequerimento',
        'numero_protocolo',
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
     * Setor destinatário do requerimento.
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }

    /**
     * Assunto (objetivo) vinculado a este requerimento.
     */
    public function assunto()
    {
        return $this->belongsTo(AssuntoRequerimento::class, 'assunto_requerimento_id');
    }
}

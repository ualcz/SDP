<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Representante extends Model
{
    protected $table = 'representantes';
    protected $fillable = [
    'usuario_id',
    'inicio_mandato',
    'fim_mandato',
    'ativo'
];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    // Indicação dos campos que poderão ser preenchidos;
    protected $fillable = [
        'usuario_id',
        'objetoDoRequerimento',
        'motivo',
        'situação',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function user()
    {
        return $this->usuario();
    }
}

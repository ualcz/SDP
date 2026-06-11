<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
        'titulo',
        'tipo',
        'data_inicio',
        'hora_inicio',
        'hora_fim',
        'descricao'
    ];
}
?>
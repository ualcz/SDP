<?php

namespace App\Models;

use App\Models\Usuario;
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
        'descricao',
        'disciplina_professor_id',
        'criado_por',
        'lembrete_enviado',
    ];

    protected $casts = [
        'lembrete_enviado' => 'boolean',
    ];

    public function oferta()
    {
        return $this->belongsTo(
            DisciplinaProfessor::class,
            'disciplina_professor_id'
        );
    }

    public function criador()
    {
        return $this->belongsTo(
            Usuario::class,
            'criado_por'
        );
    }
}
?>
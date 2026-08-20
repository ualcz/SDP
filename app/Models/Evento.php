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
    // mudei de User para usuario
    return $this->belongsTo(Usuario::class, 'criado_por');
}
}
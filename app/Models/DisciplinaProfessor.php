<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinaProfessor extends Model
{
    protected $table = 'disciplina_professor';

    protected $fillable = [
        'disciplina_id',
        'professor_id',
        'turma_codigo'
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}

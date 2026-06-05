<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $table = 'alunos';

    protected $fillable = [
        'usuario_id',
        'matricula',
        'turma_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Disciplina extends Model
{
    protected $table = 'disciplinas';

    protected $fillable = [
        'suap_id',
        'codigo',
        'nome'
    ];

    public function professores()
    {
        return $this->belongsToMany(Professor::class);
    }
    public function ofertas()
    {
        return $this->hasMany(
        DisciplinaProfessor::class
        );
    }
}
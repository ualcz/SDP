<?php

//ta errado
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professores';

    protected $fillable = [
        'nome',
        'matricula',
        'suap_id'
    ];

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class);
    }
}
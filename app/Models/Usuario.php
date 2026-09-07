<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Requerimento;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'matricula',
        'nome',
        'cpf',
        'email',
        'email_pessoal',
        'password',
        'senha_suap',
        'endereco',
        'telefone',
        'turma_codigo',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE PAPEL
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAluno(): bool
    {
        return $this->role === 'aluno';
    }

    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    public function requerimentos()
    {
        return $this->hasMany(Requerimento::class, 'usuario_id');
    }
}
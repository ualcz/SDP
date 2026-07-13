<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'matricula',
        'nome',
        'email',
        'password',
        'senha_suap',
        'turma_codigo',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function representantes()
    {
        return $this->hasMany(Representante::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE PAPEL
    |--------------------------------------------------------------------------
    */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAluno()
    {
        return $this->role === 'aluno';
    }

    public function isProfessor()
    {
        return $this->role === 'professor';
    }

    /*
    |--------------------------------------------------------------------------
    | REPRESENTANTE
    |--------------------------------------------------------------------------
    */

    public function representanteAtivo()
    {
        return $this->representantes()
            ->where('ativo', true)
            ->exists();
    }
}
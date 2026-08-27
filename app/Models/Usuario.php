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
        'email_pessoal',
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
    public function professor()
{
    return $this->hasOne(
        Professor::class,
        'matricula',
        'matricula'
    );
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
     /*
    |--------------------------------------------------------------------------
    | FUNÇÕES AUXILIARES
    |--------------------------------------------------------------------------
    */
    public function podeGerenciarEventos()
{
    return $this->isAdmin()
        || $this->isProfessor()
        || $this->representanteAtivo();
}

public function podeExcluirEventos()
{
    return $this->isAdmin();
}
}
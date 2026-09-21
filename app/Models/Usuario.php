<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Requerimento;
use App\Models\Setor;
use App\Models\Endereco;

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

    /*
    |--------------------------------------------------------------------------
    | RESPONSABILIDADE DE SETOR
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna os setores vinculados a este responsável.
     */
    public function setoresSobResponsabilidade(): BelongsToMany
    {
        return $this->belongsToMany(Setor::class, 'setor_responsavel', 'usuario_id', 'setor_id');
    }

    public function ehResponsavel(): bool
    {
        return $this->setoresSobResponsabilidade()->exists();
    }

    public function ehResponsavelDoSetor($setorId): bool
    {
        return $this->setoresSobResponsabilidade()->where('setores.id', $setorId)->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | OUTROS RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function requerimentos()
    {
        return $this->hasMany(Requerimento::class, 'usuario_id');
    }

    public function endereco()
    {
        return $this->hasOne(Endereco::class, 'usuario_id');
    }
}

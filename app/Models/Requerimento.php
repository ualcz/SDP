<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Setor;
use App\Models\AssuntoRequerimento;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    protected $fillable = [
        'usuario_id',
        'setor_id',
        'assunto_requerimento_id',
        'objetoDoRequerimento',
        'numero_protocolo',
        'motivo',
        'status',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function user()
    {
        return $this->usuario();
    }

    /**
     * Assunto (objetivo) vinculado a este requerimento.
     */
    public function assunto()
    {
        return $this->belongsTo(AssuntoRequerimento::class, 'assunto_requerimento_id');
    }

    //Relacionamento de setor com requerimento;
     public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id', 'id');
    }

    /**
     * Setor destinatário do requerimento (via assunto).
     */
    public function setorAssunto()
    {
        return $this->hasOneThrough(
            Setor::class,
            AssuntoRequerimento::class,
            'id',                      // FK em assuntos_requerimentos vinculada ao requerimento
            'id',                      // FK em setores
            'assunto_requerimento_id', // Local key em requerimentos
            'setor_id'                 // Local key em assuntos_requerimentos
        );
    }

    /**
     * Retorna a sigla ou nome resumido do setor destinatário.
     */
    public function getSetorSiglaAttribute(): string
    {
        if ($this->assunto && $this->assunto->setor) {
            return $this->assunto->setor->setor_sigla ?: $this->assunto->setor->setor_nome;
        }

        if (!empty($this->objetoDoRequerimento)) {
            $assunto = AssuntoRequerimento::with('setor')
                ->where('descricao', $this->objetoDoRequerimento)
                ->first();
            if ($assunto && $assunto->setor) {
                return $assunto->setor->setor_sigla ?: $assunto->setor->setor_nome;
            }
        }

        return 'Geral';
    }

    /**
     * Retorna o nome completo do setor.
     */
    public function getSetorNomeAttribute(): string
    {
        if ($this->assunto && $this->assunto->setor) {
            return $this->assunto->setor->setor_nome ?: $this->assunto->setor->setor_sigla;
        }

        if (!empty($this->objetoDoRequerimento)) {
            $assunto = AssuntoRequerimento::with('setor')
                ->where('descricao', $this->objetoDoRequerimento)
                ->first();
            if ($assunto && $assunto->setor) {
                return $assunto->setor->setor_nome ?: $assunto->setor->setor_sigla;
            }
        }

        return 'Setor Geral';
    }
}

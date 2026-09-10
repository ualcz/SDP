<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssuntoRequerimento extends Model
{
    protected $table = 'assuntos_requerimentos';

    protected $fillable = [
        'setor_id',
        'modelo_requerimento_id',
        'codigo',
        'descricao',
        'observacao',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo'  => 'boolean',
        'ordem'  => 'integer',
    ];

    /**
     * Setor ao qual este assunto pertence.
     */
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }

    /**
     * Alias de retrocompatibilidade para o setor.
     */
    public function modelo()
    {
        return $this->setor();
    }

    /**
     * Todos os documentos vinculados a este assunto.
     */
    public function documentos()
    {
        return $this->hasMany(DocumentoAssunto::class, 'assunto_requerimento_id');
    }

    /**
     * Apenas os documentos marcados como obrigatórios.
     */
    public function documentosObrigatorios()
    {
        return $this->hasMany(DocumentoAssunto::class, 'assunto_requerimento_id')
                    ->where('obrigatorio', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssuntoRequerimento extends Model
{
    protected $table = 'assuntos_requerimentos';

    protected $fillable = [
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

    public function modelo()
    {
        return $this->belongsTo(ModeloRequerimento::class, 'modelo_requerimento_id');
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


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoAssunto extends Model
{
    protected $table = 'documentos_assunto';

    protected $fillable = [
        'assunto_requerimento_id',
        'nome',
        'descricao',
        'link_modelo',
        'arquivo_modelo',
        'obrigatorio',
        'exige_documentos_responsaveis',
        'tipos_aceitos',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'exige_documentos_responsaveis' => 'boolean',
    ];

    /**
     * Assunto ao qual este documento pertence.
     */
    public function assunto()
    {
        return $this->belongsTo(AssuntoRequerimento::class, 'assunto_requerimento_id');
    }
}

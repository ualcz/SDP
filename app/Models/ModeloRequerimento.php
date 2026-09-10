<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ModeloRequerimento extends Model
{
    protected $table = 'modelos_requerimentos';

    protected $fillable = [
        'identificador',
        'setor_chave',
        'setor_sigla',
        'setor_nome',
        'email',
        'titulo',
        'processo_prefixo',
        'rodape_contato',
        'ativo',
    ];

    protected $casts = [
        'observacoes' => 'array',
        'ativo' => 'boolean',
    ];

    public function assuntos()
    {
        return $this->hasMany(AssuntoRequerimento::class, 'modelo_requerimento_id')->orderBy('ordem');
    }

    public function assuntosAtivos()
    {
        return $this->hasMany(AssuntoRequerimento::class, 'modelo_requerimento_id')
            ->where('ativo', true)
            ->orderBy('ordem');
    }

    /**
     * Retorna os modelos formatados para uso nos controllers e views,
     * com fallback automático para o config/modelos_requerimentos.php.
     */
    public static function obterModelosFormatados(): array
    {
        try {
            if (!Schema::hasTable('modelos_requerimentos')) {
                return config('modelos_requerimentos.modelos', []);
            }

            $modelosBanco = static::with(['assuntosAtivos'])->where('ativo', true)->get();

            if ($modelosBanco->isEmpty()) {
                return config('modelos_requerimentos.modelos', []);
            }

            $resultado = [];
            foreach ($modelosBanco as $mod) {
                $objetos = [];
                $assuntosDetalhes = [];
                foreach ($mod->assuntosAtivos as $assunto) {
                    $chave = $assunto->codigo ?: str_pad((string) $assunto->id, 2, '0', STR_PAD_LEFT);
                    $objetos[$chave] = $assunto->descricao;
                    $assuntosDetalhes[] = [
                        'id' => $assunto->id,
                        'codigo' => $chave,
                        'descricao' => $assunto->descricao,
                        'observacao' => $assunto->observacao,
                        'ordem' => $assunto->ordem,
                    ];
                }

                $resultado[$mod->identificador] = [
                    'id' => $mod->id,
                    'identificador' => $mod->identificador,
                    'setor_chave' => $mod->setor_chave,
                    'setor_sigla' => $mod->setor_sigla,
                    'setor_nome' => $mod->setor_nome,
                    'email' => $mod->email,
                    'titulo' => $mod->titulo,
                    'processo_prefixo' => $mod->processo_prefixo ?: '23720',
                    'objetos' => $objetos,
                    'assuntos_detalhes' => $assuntosDetalhes,
                    'observacoes' => $mod->observacoes ?? [],
                    'rodape_contato' => $mod->rodape_contato,
                ];
            }

            return $resultado;
        } catch (\Throwable $e) {
            return config('modelos_requerimentos.modelos', []);
        }
    }
}

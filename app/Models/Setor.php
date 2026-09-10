<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setor extends Model
{
    protected $table = 'setores';

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
        return $this->hasMany(AssuntoRequerimento::class, 'setor_id')->orderBy('ordem');
    }

    public function assuntosAtivos()
    {
        return $this->hasMany(AssuntoRequerimento::class, 'setor_id')
            ->where('ativo', true)
            ->orderBy('ordem');
    }

    public function requerimentos()
    {
        return $this->hasMany(Requerimento::class, 'setor_id');
    }

    /**
     * Retorna os setores formatados para uso nos controllers e views,
     * com fallback automático para o config/modelos_requerimentos.php.
     */
    public static function obterSetoresFormatados(): array
    {
        try {
            if (!Schema::hasTable('setores')) {
                return config('modelos_requerimentos.modelos', []);
            }

            $setoresBanco = static::with(['assuntosAtivos.documentos'])->where('ativo', true)->get();

            if ($setoresBanco->isEmpty()) {
                return config('modelos_requerimentos.modelos', []);
            }

            $resultado = [];
            foreach ($setoresBanco as $mod) {
                $objetos = [];
                $assuntosDetalhes = [];
                foreach ($mod->assuntosAtivos as $assunto) {
                    $chave = $assunto->codigo ?: str_pad((string) $assunto->id, 2, '0', STR_PAD_LEFT);
                    $objetos[$chave] = $assunto->descricao;
                    $assuntosDetalhes[] = [
                        'id'                     => $assunto->id,
                        'codigo'                 => $chave,
                        'descricao'              => $assunto->descricao,
                        'observacao'             => $assunto->observacao,
                        'ordem'                  => $assunto->ordem,
                        'documentos_obrigatorios' => $assunto->documentos
                            ->map(fn($d) => [
                                'nome'          => $d->nome,
                                'descricao'     => $d->descricao,
                                'obrigatorio'   => $d->obrigatorio,
                                'tipos_aceitos' => $d->tipos_aceitos,
                            ])->values()->toArray(),
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

    /**
     * Alias para compatibilidade com chamadas legadas
     */
    public static function obterModelosFormatados(): array
    {
        return static::obterSetoresFormatados();
    }
}

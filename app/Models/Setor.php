<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Setor extends Model
{
    protected $table = 'setores';

    protected $fillable = [
        'setor_sigla',
        'setor_nome',
        'email',
        'titulo',
        'ativo',
    ];

    protected $casts = [
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

    public function responsaveis(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'setor_responsavel', 'setor_id', 'usuario_id');
    }

    /**
     * Retorna os setores formatados para uso nos controllers e views.
     */
    public static function obterSetoresFormatados(): array
    {
        try {
            // Incluído 'responsaveis' no Eager Loading para evitar queries N+1
            $setoresBanco = static::with(['responsaveis', 'assuntosAtivos.documentos'])
                ->where('ativo', true)
                ->get();

            if ($setoresBanco->isEmpty()) {
                return [];
            }

            $resultado = [];
            foreach ($setoresBanco as $mod) {
                $objetos = [];
                $assuntosDetalhes = [];

                foreach ($mod->assuntosAtivos as $assunto) {
                    $chave = str_pad((string) $assunto->id, 2, '0', STR_PAD_LEFT);
                    $objetos[$chave] = $assunto->descricao;
                    $assuntosDetalhes[] = [
                        'id'                     => $assunto->id,
                        'descricao'              => $assunto->descricao,
                        'observacao'             => $assunto->observacao,
                        'ordem'                  => $assunto->ordem,
                        'documentos_obrigatorios' => $assunto->documentos
                            ->map(fn($d) => [
                                'id'            => $d->id,
                                'nome'          => $d->nome,
                                'descricao'     => $d->descricao,
                                'obrigatorio'   => $d->obrigatorio,
                                'tipos_aceitos' => $d->tipos_aceitos,
                            ])->values()->toArray(),
                    ];
                }

                $resultado[$mod->id] = [
                    'id'            => $mod->id,
                    'setor_sigla'   => $mod->setor_sigla,
                    'setor_nome'    => $mod->setor_nome,
                    'email'         => $mod->email,
                    'titulo'        => $mod->titulo,
                    'responsaveis'  => $mod->responsaveis->map(fn($r) => [
                        'id'    => $r->id,
                        'nome'  => $r->nome ?? $r->name,
                        'email' => $r->email,
                    ])->toArray(),
                        'objetos'           => $objetos,
                        'assuntos_detalhes' => $assuntosDetalhes,
                    ];
            }

            return $resultado;
        } catch (\Throwable $e) {
            return [];
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

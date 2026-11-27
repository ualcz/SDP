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
     * Retorna a lista de e-mails válidos para envio de notificações do setor.
     * Prioriza o e-mail cadastrado no setor (aceitando múltiplos separados por vírgula ou ponto e vírgula).
     * Se não houver e-mail cadastrado no setor, utiliza os e-mails dos responsáveis vinculados.
     */
    public function obterEmailsNotificacao(): array
    {
        $emails = [];

        if (!empty($this->email)) {
            $partes = preg_split('/[,;]+/', $this->email);
            foreach ($partes as $parte) {
                $e = trim($parte);
                if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $e;
                }
            }
        }

        if (empty($emails)) {
            $responsaveis = $this->relationLoaded('responsaveis') ? $this->responsaveis : $this->responsaveis()->get();
            foreach ($responsaveis as $resp) {
                $e = trim($resp->email ?? $resp->email_pessoal ?? '');
                if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $e;
                }
            }
        }

        return array_values(array_unique($emails));
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
                        'link_norma'             => $assunto->link_norma,
                        'ordem'                  => $assunto->ordem,
                        'documentos_obrigatorios' => $assunto->documentos
                            ->map(fn($d) => [
                                'id'            => $d->id,
                                'nome'          => $d->nome,
                                'descricao'     => $d->descricao,
                                'link_modelo'   => $d->link_modelo,
                                'arquivo_modelo' => $d->arquivo_modelo,
                                'obrigatorio'   => $d->obrigatorio,
                                'exige_documentos_responsaveis' => $d->exige_documentos_responsaveis,
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Setor;
use App\Models\AssuntoRequerimento;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\RequerimentoObserver;
use App\Mail\AtualizacaoRequerimentoMail;
use Illuminate\Support\Facades\Mail;

#[ObservedBy([RequerimentoObserver::class])]
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

    public function historicos()
    {
        return $this->hasMany(HistoricoRequerimento::class)->oldest();
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoRequerimento::class, 'requerimento_id');
    }


    public function emailThreadSubject(): string
    {
        return 'Requerimento #' . ($this->numero_protocolo ?? $this->id) . ' [' . $this->objetoDoRequerimento . '] - ' . ($this->usuario?->nome ?? 'Aluno');
    }

    /**
     * Envia e-mail de atualização/tramitação para o aluno e setor.
     */
    public function notificarPartes(string $mensagem, string $remetente, array $arquivos = []): void
    {
        $setor = $this->setor;
        $aluno = $this->usuario;

        $emailsSetor = [];
        if (!empty($setor->email)) {
            $emailsSetor = is_array($setor->email) ? $setor->email : [$setor->email];
        }
        $emailsSetor = array_values(array_unique(array_filter(array_map('trim', $emailsSetor))));

        $emailsAluno = array_filter([$aluno?->email_pessoal, $aluno?->email]);
        $emailsAluno = array_values(array_unique(array_filter(array_map('trim', $emailsAluno))));

        if ($remetente === 'setor') {
            $to = $emailsAluno;
            $cc = $emailsSetor;
        } else {
            $to = $emailsSetor;
            $cc = $emailsAluno;
        }

        if (!empty($to)) {
            $mailer = Mail::to($to);
            if (!empty($cc)) {
                $mailer->cc($cc);
            }
            $mailer->send(new AtualizacaoRequerimentoMail($this, $mensagem, $remetente, $arquivos));
        }
    }
}

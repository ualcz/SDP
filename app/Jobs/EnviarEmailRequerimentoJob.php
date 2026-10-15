<?php

namespace App\Jobs;

use App\Mail\InformacoesAlunoMail;
use App\Models\DocumentoRequerimento;
use App\Models\Requerimento;
use App\Services\RequerimentoEmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarEmailRequerimentoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Número de tentativas do job em caso de falha transitória de SMTP.
     */
    public int $tries = 3;

    /**
     * Timeout em segundos para o envio.
     */
    public int $timeout = 120;

    /**
     * @param int $requerimentoId
     * @param array $destinatarios ['para' => [...], 'cc' => [...]]
     * @param int|null $pdfDocumentoId ID do DocumentoRequerimento salvo no storage
     * @param array $anexosNaoMescladosCaminhos Caminhos no storage disk local de arquivos não mesclados
     * @param string|null $mensagem
     * @param string|null $objeto
     * @param string $setorNome
     * @param string|null $setorChave
     */
    public function __construct(
        public int $requerimentoId,
        public array $destinatarios,
        public ?int $pdfDocumentoId = null,
        public array $anexosNaoMescladosCaminhos = [],
        public ?string $mensagem = null,
        public ?string $objeto = null,
        public string $setorNome = '',
        public ?string $setorChave = null
    ) {}

    public function handle(RequerimentoEmailService $servicoEmail): void
    {
        $requerimento = Requerimento::with(['usuario.endereco', 'setor'])->find($this->requerimentoId);

        if (!$requerimento) {
            Log::warning("EnviarEmailRequerimentoJob: Requerimento #{$this->requerimentoId} não encontrado no BD.");
            return;
        }

        $aluno = $requerimento->usuario;
        if (!$aluno) {
            Log::warning("EnviarEmailRequerimentoJob: Aluno do Requerimento #{$this->requerimentoId} não encontrado.");
            return;
        }

        $pdfInfo = null;
        if ($this->pdfDocumentoId) {
            $docPdf = DocumentoRequerimento::find($this->pdfDocumentoId);
            if ($docPdf) {
                $pdfInfo = [
                    'nome' => $docPdf->nome_original ?: 'Requerimento.pdf',
                    'caminho' => $docPdf->caminho,
                ];
            }
        }

        $mailable = new InformacoesAlunoMail(
            aluno: $aluno,
            setorNome: $this->setorNome ?: ($requerimento->setor?->setor_nome ?? 'Setor Responsável'),
            mensagem: $this->mensagem,
            arquivos: $this->anexosNaoMescladosCaminhos,
            objeto: $this->objeto,
            setorChave: $this->setorChave,
            requerimento: $requerimento,
            pdfRequerimento: $pdfInfo
        );

        $servicoEmail->enviar($mailable, $this->destinatarios);

        Log::info("EnviarEmailRequerimentoJob: E-mail do Requerimento #{$this->requerimentoId} enviado com sucesso.");
    }
}

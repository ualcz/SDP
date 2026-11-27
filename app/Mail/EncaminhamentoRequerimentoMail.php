<?php

namespace App\Mail;

use App\Models\Requerimento;
use App\Models\Setor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EncaminhamentoRequerimentoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Requerimento $requerimento
     * @param Setor $setorOrigem Setor que está encaminhando ou devolvendo
     * @param Setor $setorDestino Setor que receberá a notificação
     * @param string $mensagem Despacho, parecer ou orientação
     * @param string $tipo 'encaminhamento' ou 'resposta'
     * @param array $arquivos Documentos anexados
     */
    public function __construct(
        public Requerimento $requerimento,
        public Setor $setorOrigem,
        public Setor $setorDestino,
        public string $mensagem,
        public string $tipo = 'encaminhamento',
        public ?array $arquivos = []
    ) {}

    public function headers(): Headers
    {
        $messageId = $this->requerimento->email_message_id;

        return new Headers(
            references: $messageId ? [$messageId] : [],
            text: $messageId ? ['In-Reply-To' => '<' . $messageId . '>'] : [],
        );
    }

    public function build()
    {
        // Mantém exatamente o mesmo assunto da thread do requerimento para agrupar na mesma conversa no Gmail/Outlook
        $email = $this->subject($this->requerimento->emailThreadSubject())
                      ->markdown('emails.requerimento_encaminhado');

        // Garante que qualquer resposta via cliente de e-mail vá para o setor de origem, e nunca para o aluno
        $emailsOrigem = $this->setorOrigem->obterEmailsNotificacao();
        if (!empty($emailsOrigem)) {
            $email->replyTo($emailsOrigem);
        }

        // Anexa o PDF do requerimento oficial do aluno e eventuais documentos iniciais
        if ($this->tipo === 'encaminhamento') {
            $anexadoPdf = false;

            // 1. Busca o PDF oficial do requerimento salvo no storage
            $docPdf = $this->requerimento->documentos()
                ->where('nome_documento', 'Requerimento enviado')
                ->latest()
                ->first();

            if (!$docPdf) {
                $primeiroHistorico = $this->requerimento->historicos()->first();
                if ($primeiroHistorico) {
                    $docPdf = $this->requerimento->documentos()
                        ->where('historico_requerimento_id', $primeiroHistorico->id)
                        ->where(function ($q) {
                            $q->where('extensao', 'pdf')
                              ->orWhere('nome_documento', 'like', '%Requerimento%')
                              ->orWhere('nome_original', 'like', '%Requerimento%');
                        })
                        ->first();
                }
            }

            if ($docPdf && Storage::disk('local')->exists($docPdf->caminho)) {
                $email->attach(Storage::disk('local')->path($docPdf->caminho), [
                    'as' => $docPdf->nome_original ?: 'Requerimento_Aluno.pdf',
                    'mime' => 'application/pdf',
                ]);
                $anexadoPdf = true;
            }

            // 2. Se por algum motivo o arquivo físico não foi encontrado em disco, gera dinamicamente
            if (!$anexadoPdf && $this->requerimento->usuario) {
                try {
                    $pdf = \App\Http\Controllers\RequerimentoPdfController::criarPdf(
                        aluno: $this->requerimento->usuario,
                        setorNome: $this->requerimento->setorNome,
                        setorChave: (string) $this->requerimento->setor_id,
                        objeto: $this->requerimento->objetoDoRequerimento,
                        mensagem: $this->requerimento->motivo,
                        numeroProtocolo: $this->requerimento->numero_protocolo
                    );
                    $nomeFallback = 'Requerimento_' . ($this->requerimento->numero_protocolo ? str_replace('/', '-', $this->requerimento->numero_protocolo) : $this->requerimento->id) . '.pdf';
                    $email->attachData($pdf->output(), $nomeFallback, [
                        'mime' => 'application/pdf',
                    ]);
                } catch (\Throwable $e) {
                    Log::warning("Não foi possível gerar PDF dinâmico do requerimento para encaminhamento: " . $e->getMessage());
                }
            }

            // 3. Anexa outros documentos iniciais enviados pelo aluno (caso não tenham sido mesclados no PDF principal)
            $primeiroHistorico = $this->requerimento->historicos()->first();
            if ($primeiroHistorico) {
                $outrosDocs = $this->requerimento->documentos()
                    ->where('historico_requerimento_id', $primeiroHistorico->id)
                    ->where('id', '!=', $docPdf?->id ?? 0)
                    ->get();

                foreach ($outrosDocs as $outroDoc) {
                    if (Storage::disk('local')->exists($outroDoc->caminho)) {
                        $email->attach(Storage::disk('local')->path($outroDoc->caminho), [
                            'as' => $outroDoc->nome_original ?: $outroDoc->nome_documento,
                            'mime' => $outroDoc->mime_type ?? 'application/octet-stream',
                        ]);
                    }
                }
            }
        }

        // Anexa arquivos anexados no despacho do encaminhamento (se houver)
        if (!empty($this->arquivos)) {
            foreach ($this->arquivos as $arquivo) {
                if ($arquivo instanceof \Illuminate\Http\UploadedFile) {
                    $email->attach($arquivo->getRealPath(), [
                        'as' => $arquivo->getClientOriginalName(),
                        'mime' => $arquivo->getMimeType(),
                    ]);
                } elseif (is_string($arquivo) && file_exists($arquivo)) {
                    $email->attach($arquivo);
                }
            }
        }

        return $email;
    }
}

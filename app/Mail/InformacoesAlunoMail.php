<?php

namespace App\Mail;

use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InformacoesAlunoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Usuario $aluno
     * @param string $setorNome
     * @param string|null $mensagem
     * @param array $arquivos Array de UploadedFile ou caminhos de arquivos
     */
    public function __construct(
        public Usuario $aluno,
        public string $setorNome,
        public ?string $mensagem = null,
        public array $arquivos = [],
        public ?string $objeto = null,
        public ?string $setorChave = null
    ) {}

    /**
     * Define o assunto e cabeçalhos do e-mail.
     */
    public function envelope(): Envelope
    {
        $assunto = $this->objeto 
            ? 'Requerimento [' . $this->objeto . '] - ' . $this->aluno->nome 
            : 'Informações Cadastrais do Aluno: ' . $this->aluno->nome;

        return new Envelope(
            subject: $assunto,
        );
    }

    /**
     * Define o template Blade usado.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.informacoes_aluno',
        );
    }

    /**
     * Anexa dinamicamente o PDF do requerimento e qualquer outro arquivo enviado.
     */
    public function attachments(): array
    {
        $anexos = [];

        // 1. Gera o PDF do requerimento a partir da view Blade
        try {
            $pdf = Pdf::loadView('pdf.requerimento', [
                'aluno' => $this->aluno,
                'setorNome' => $this->setorNome,
                'setorChave' => $this->setorChave,
                'objeto' => $this->objeto,
                'mensagem' => $this->mensagem,
            ])->setPaper('a4', 'portrait');

            $nomePdf = 'Requerimento_' . Str::slug($this->aluno->nome) . '_' . date('Ymd_His') . '.pdf';

            $anexos[] = Attachment::fromData(fn () => $pdf->output(), $nomePdf)
                ->withMime('application/pdf');
        } catch (\Throwable $e) {
            logger()->error('Erro ao gerar PDF do requerimento: ' . $e->getMessage());
        }

        // 2. Anexa arquivos enviados manualmente pelo aluno
        foreach ($this->arquivos as $arquivo) {
            if ($arquivo instanceof \Illuminate\Http\UploadedFile) {
                $anexos[] = Attachment::fromPath($arquivo->getRealPath())
                    ->as($arquivo->getClientOriginalName())
                    ->withMime($arquivo->getMimeType());
            } elseif (is_string($arquivo) && file_exists($arquivo)) {
                $anexos[] = Attachment::fromPath($arquivo);
            }
        }

        return $anexos;
    }
}


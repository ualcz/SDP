<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
        public array $arquivos = []
    ) {}

    /**
     * Define o assunto e cabeçalhos do e-mail.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informações Cadastrais do Aluno: ' . $this->aluno->nome,
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
     * Anexa dinamicamente qualquer arquivo enviado.
     */
    public function attachments(): array
    {
        $anexos = [];

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

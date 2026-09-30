<?php

namespace App\Mail;

use App\Models\Requerimento;
use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
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
        public array $arquivos = [],
        public ?string $objeto = null,
        public ?string $setorChave = null,
        public ?Requerimento $requerimento = null,
        public ?array $pdfRequerimento = null
    ) {}

    /**
     * Define o assunto e cabeçalhos do e-mail.
     */
    public function envelope(): Envelope
    {
        $assunto = $this->objeto
            ? ($this->requerimento?->emailThreadSubject() ?? 'Requerimento [' . $this->objeto . '] - ' . $this->aluno->nome)
            : 'Informações Cadastrais do Aluno: ' . $this->aluno->nome;

        // Reply-To aponta para o e-mail pessoal do aluno.
        // Quando o setor clicar em "Responder", a resposta vai direto para o aluno.
        // Usa o e-mail institucional apenas como fallback, caso o pessoal não exista.
        $replyToEmail = $this->aluno->email_pessoal ?: $this->aluno->email;
        $replyTo = $replyToEmail
            ? [new Address($replyToEmail, $this->aluno->nome)]
            : [];

        return new Envelope(
            subject: $assunto,
            replyTo: $replyTo ?: null,
        );
    }

    public function headers(): Headers
    {
        return new Headers(messageId: $this->requerimento?->email_message_id);
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

    public function attachments(): array
    {
        $anexos = [];

        if ($this->pdfRequerimento !== null) {
            $anexos[] = Attachment::fromData(fn () => $this->pdfRequerimento['conteudo'], $this->pdfRequerimento['nome'])
                ->withMime('application/pdf');
        }

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


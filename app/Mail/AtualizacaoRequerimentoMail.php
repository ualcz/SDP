<?php

namespace App\Mail;

use App\Models\Requerimento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class AtualizacaoRequerimentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Requerimento $requerimento,
        public string $mensagem,
        public string $autor, // 'setor' ou 'aluno'
        public ?array $arquivos = [],
        public bool $solicitaNovoDocumento = false
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
        $email = $this->subject($this->requerimento->emailThreadSubject())
                     ->markdown('emails.requerimento_atualizado');

        // Anexa arquivos se houver (ex: nova documentação de correção)
        foreach ($this->arquivos as $arquivo) {
            if ($arquivo instanceof \Illuminate\Http\UploadedFile) {
                $email->attach($arquivo->getRealPath(), [
                    'as' => $arquivo->getClientOriginalName(),
                    'mime' => $arquivo->getMimeType(),
                ]);
            }
        }

        return $email;
    }
}

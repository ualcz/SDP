<?php

namespace App\Mail;

use App\Http\Controllers\RequerimentoPdfController;
use App\Models\Usuario;
use App\Services\Pdf\PdfMergerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
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
     * Anexa dinamicamente o PDF unificado (Requerimento + Anexos mesclados).
     */
    public function attachments(): array
    {
        $anexos = [];
        $arquivosNaoMesclados = [];

        // 1. Gera o PDF do requerimento através do controller especializado
        try {
            $pdf = RequerimentoPdfController::criarPdf(
                aluno: $this->aluno,
                setorNome: $this->setorNome,
                setorChave: $this->setorChave,
                objeto: $this->objeto,
                mensagem: $this->mensagem,
            );

            $pdfConteudo = $pdf->output();

            // Se houver anexos enviados, mescla tudo (requerimento + imagens/PDFs) em um único PDF
            if (!empty($this->arquivos)) {
                $merger = app(PdfMergerService::class);
                $pdfConteudo = $merger->mesclarComAnexos($pdfConteudo, $this->arquivos, $arquivosNaoMesclados);
            }

            $nomePdf = 'Requerimento_' . Str::slug($this->aluno->nome) . '_' . date('Ymd_His') . '.pdf';

            $anexos[] = Attachment::fromData(fn () => $pdfConteudo, $nomePdf)
                ->withMime('application/pdf');
        } catch (\Throwable $e) {
            logger()->error('Erro ao gerar/mesclar PDF do requerimento: ' . $e->getMessage());
            $arquivosNaoMesclados = $this->arquivos;
        }

        // 2. Anexa separadamente apenas eventuais arquivos que não puderam ser convertidos/mesclados (ex: docx, zip)
        foreach ($arquivosNaoMesclados as $arquivo) {
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


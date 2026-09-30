<?php

namespace App\Services;

use App\Mail\InformacoesAlunoMail;
use App\Models\Setor;
use App\Models\Usuario;
use Illuminate\Support\Facades\Mail;

class RequerimentoEmailService
{
    public function resolverDestinatarios(Setor $setor, Usuario $aluno, ?string $emailAdicional = null): ?array
    {
        $emailsSetor = $setor->email;
        $emailsSetor = is_array($emailsSetor) ? $emailsSetor : [$emailsSetor];

        $emailsAluno = array_filter([
            $aluno->email_pessoal,
            $aluno->email,
            $emailAdicional,
        ]);

        $emailsSetor = array_values(array_unique(array_filter(array_map('trim', $emailsSetor))));
        $emailsAluno = array_values(array_unique(array_filter(array_map('trim', $emailsAluno))));
        $emailsAluno = array_values(array_diff($emailsAluno, $emailsSetor));

        if (empty($emailsSetor) && empty($emailsAluno)) {
            return null;
        }

        return [
            'para' => !empty($emailsSetor) ? $emailsSetor : $emailsAluno,
            'cc' => !empty($emailsSetor) ? $emailsAluno : [],
        ];
    }

    public function enviar(InformacoesAlunoMail $mailable, array $destinatarios): void
    {
        $mailer = Mail::to($destinatarios['para']);

        if (!empty($destinatarios['cc'])) {
            $mailer->cc($destinatarios['cc']);
        }

        $mailer->send($mailable);
    }
}
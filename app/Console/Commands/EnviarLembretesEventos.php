<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Evento;
use App\Models\Usuario;
use Carbon\Carbon;

class EnviarLembretesEventos extends Command
{
    protected $signature = 'eventos:enviar-lembretes';

    protected $description = 'Envia lembretes de eventos que acontecerão em 24 horas';

    public function handle()
    {
        $agora = Carbon::now();

        $this->info(
            "Agora: " . $agora->format('d/m/Y H:i:s')
        );

        /*
         * Busca eventos que ainda não receberam
         * o lembrete.
         */
        $eventos = Evento::where('lembrete_enviado', false)
            ->whereNotNull('data_inicio')
            ->whereNotNull('hora_inicio')
            ->whereNotNull('disciplina_professor_id')
            ->get();

        $this->info(
            "Eventos encontrados: " . $eventos->count()
        );

        foreach ($eventos as $evento) {

            /*
             * Monta a data e hora completas do evento.
             */
            $dataHoraEvento = Carbon::parse(
                $evento->data_inicio . ' ' . $evento->hora_inicio
            );

            /*
             * Calcula exatamente quando o lembrete
             * deve ser enviado.
             */
            $momentoLembrete = $dataHoraEvento->copy()
                ->subHours(24);

            /*
             * Tolerância de 5 minutos para o Scheduler.
             */
            $inicioJanela = $momentoLembrete->copy()
                ->subMinutes(5);

            $fimJanela = $momentoLembrete->copy()
                ->addMinutes(5);

            $this->line(
                "Evento: {$evento->titulo} | " .
                "Evento em: {$dataHoraEvento->format('d/m/Y H:i:s')} | " .
                "Lembrete em: {$momentoLembrete->format('d/m/Y H:i:s')}"
            );

            /*
             * Verifica se agora está dentro da janela
             * de envio do lembrete.
             */
            if (
                $agora->greaterThanOrEqualTo($inicioJanela) &&
                $agora->lessThanOrEqualTo($fimJanela)
            ) {

                $this->info(
                    ">>> HORA DO LEMBRETE: {$evento->titulo}"
                );

                /*
                 * Descobre a relação disciplina/professor
                 * associada ao evento.
                 */
                $disciplinaProfessor = $evento->oferta;

                if (!$disciplinaProfessor) {

                    $this->error(
                        "Evento {$evento->id} não possui uma relação " .
                        "disciplina_professor válida."
                    );

                    continue;
                }

                /*
                 * Pega o código da turma diretamente
                 * da tabela disciplina_professor.
                 */
                $turmaCodigo = $disciplinaProfessor->turma_codigo;

                if (!$turmaCodigo) {

                    $this->error(
                        "Evento {$evento->id} não possui turma_codigo."
                    );

                    continue;
                }

                $this->info(
                    "Turma do evento: {$turmaCodigo}"
                );

                /*
                 * Busca todos os alunos da mesma turma
                 * que possuem e-mail pessoal cadastrado.
                 */
                $usuarios = Usuario::where(
                    'turma_codigo',
                    $turmaCodigo
                )
                ->whereNotNull('email_pessoal')
                ->where('email_pessoal', '!=', '')
                ->get();

                $this->info(
                    "Destinatários encontrados: " .
                    $usuarios->count()
                );

                /*
                 * Se não houver ninguém para receber,
                 * não marca o evento como enviado.
                 */
                if ($usuarios->isEmpty()) {

                    $this->warn(
                        "Nenhum aluno com e-mail pessoal " .
                        "encontrado na turma {$turmaCodigo}."
                    );

                    continue;
                }

                $envioComSucesso = true;

                try {

                    /*
                     * Envia individualmente para cada aluno.
                     *
                     * Dessa forma os alunos não enxergam
                     * o e-mail dos outros destinatários.
                     */
                    foreach ($usuarios as $usuario) {

                        $email = $usuario->email_pessoal;

                        $this->info(
                            "Enviando e-mail para: {$email}"
                        );

                        Mail::raw(
                            "Olá, {$usuario->nome}!\n\n" .

                            "Este é um lembrete do SCAAE.\n\n" .

                            "Evento: {$evento->titulo}\n" .

                            "Data: " .
                            $dataHoraEvento->format('d/m/Y') .
                            "\n" .

                            "Horário: " .
                            $dataHoraEvento->format('H:i') .
                            "\n\n" .

                            "O evento acontecerá em aproximadamente 24 horas.\n\n" .

                            "Sistema de Controle de Avaliações " .
                            "e Atividades Escolares - SCAAE",

                            function ($message) use ($email, $evento) {

                                $message
                                    ->to($email)
                                    ->subject(
                                        'Lembrete: ' . $evento->titulo
                                    );
                            }
                        );

                        $this->info(
                            "E-mail enviado para {$email}"
                        );
                    }

                    /*
                     * Só marca como enviado depois que
                     * todos os destinatários foram processados.
                     */
                    if ($envioComSucesso) {

                        $evento->update([
                            'lembrete_enviado' => true,
                        ]);

                        $this->info(
                            "Lembrete concluído: {$evento->titulo}"
                        );
                    }

                } catch (\Throwable $e) {

                    $this->error(
                        "Erro ao enviar lembrete de " .
                        "{$evento->titulo}: " .
                        $e->getMessage()
                    );

                    /*
                     * Continua como false para que o Scheduler
                     * possa tentar novamente.
                     */
                    $envioComSucesso = false;
                }
            }
        }

        $this->info('Comando finalizado.');

        return Command::SUCCESS;
    }
}
?>
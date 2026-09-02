<?php

namespace App\Services\Suap;

use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class SuapSyncService
{
    public function __construct(
        protected Browser $browser,
        protected EmailPessoalScraper $emailScraper,
        protected TurmaAlunoScraper $turmaScraper
    ) {}

    /**
     * Sincroniza dados complementares via Web Scraping (Email Pessoal, Turma)
     */
    public function sincronizar(Usuario $usuario, string $senhaLimpa): void
    {
        try {
            // Executa login simulado no SUAP
            $logado = $this->browser->login($usuario->matricula, $senhaLimpa);
            if (!$logado) {
                Log::info("Web Scraping: Não foi possível autenticar no SUAP web para {$usuario->matricula}");
                return;
            }

            $updates = [];

            if ($usuario->isAluno()) {
                // Página principal do aluno (extrai turma)
                $paginaAluno = $this->browser->get("/edu/aluno/{$usuario->matricula}/");
                if ($paginaAluno) {
                    $turma = $this->turmaScraper->codigoAtual($paginaAluno);
                    if ($turma) {
                        $updates['turma_codigo'] = $turma;
                    }
                }

                // Página de dados pessoais do aluno (extrai email pessoal)
                $paginaDadosPessoais = $this->browser->get("/edu/aluno/{$usuario->matricula}/?tab=dados_pessoais");
                if ($paginaDadosPessoais) {
                    $emailPessoal = $this->emailScraper->extrair($paginaDadosPessoais);
                    if ($emailPessoal) {
                        $updates['email_pessoal'] = $emailPessoal;
                    }
                }
            } else {
                // Servidor - página de dados pessoais
                $paginaServidor = $this->browser->get("/edu/servidor/{$usuario->matricula}/?tab=dados_pessoais");
                if (!$paginaServidor) {
                    $paginaServidor = $this->browser->get("/rh/servidor/{$usuario->matricula}/");
                }

                if ($paginaServidor) {
                    $emailPessoal = $this->emailScraper->extrair($paginaServidor);
                    if ($emailPessoal) {
                        $updates['email_pessoal'] = $emailPessoal;
                    }
                }
            }

            if (!empty($updates)) {
                $usuario->update($updates);
                Log::info("Web Scraping: Dados sincronizados com sucesso para {$usuario->matricula}", $updates);
            }
        } catch (\Throwable $e) {
            Log::error("Erro na sincronização de web scraping: " . $e->getMessage());
        }
    }
}

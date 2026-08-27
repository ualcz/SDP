<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Services\Suap\Browser;
use App\Services\Suap\TurmaScraper;
use App\Services\Suap\ProfessorScraper;
use App\Services\Suap\TurmaAlunoScraper;
use App\Services\Suap\EmailPessoalScraper;

use App\Models\Disciplina;
use App\Models\Professor;

class SuapTestController extends Controller
{
    public function index(
        Browser $browser,
        TurmaScraper $turmaScraper,
        ProfessorScraper $professorScraper,
        TurmaAlunoScraper $turmaAlunoScraper,
        EmailPessoalScraper $emailPessoalScraper
    ) {
        $usuario = Auth::user();

        $senha = Crypt::decryptString(
            $usuario->senha_suap
        );

        /*
        |--------------------------------------------------------------------------
        | Login no SUAP
        |--------------------------------------------------------------------------
        */

        $browser->login(
            $usuario->matricula,
            $senha
        );

        /*
        |--------------------------------------------------------------------------
        | Página principal do aluno
        | Usada para descobrir a turma
        |--------------------------------------------------------------------------
        */

        $paginaAluno = $browser->get(
            "/edu/aluno/{$usuario->matricula}/"
        );

        /*
        |--------------------------------------------------------------------------
        | Descobre o código da turma
        |--------------------------------------------------------------------------
        */

        $codigoTurma = $turmaAlunoScraper->codigoAtual(
            $paginaAluno
        );

        /*
        |--------------------------------------------------------------------------
        | Página de dados pessoais
        | Usada para descobrir o e-mail pessoal
        |--------------------------------------------------------------------------
        */

        $paginaDadosPessoais = $browser->get(
            "/edu/aluno/{$usuario->matricula}/?tab=dados_pessoais"
        );

        /*
        |--------------------------------------------------------------------------
        | Extrai o e-mail pessoal
        |--------------------------------------------------------------------------
        */

        $emailPessoal = $emailPessoalScraper->extrair(
            $paginaDadosPessoais
        );

        /*
        |--------------------------------------------------------------------------
        | Atualiza os dados do usuário
        |--------------------------------------------------------------------------
        */

        $usuario->update([
            'turma_codigo' => $codigoTurma,
            'email_pessoal' => $emailPessoal,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Lista as disciplinas
        |--------------------------------------------------------------------------
        */

        $pagina = $browser->get(
            '/edu/salas_virtuais/'
        );

        $turmas = $turmaScraper->listar(
            $pagina
        );

        /*
        |--------------------------------------------------------------------------
        | Descobre os professores de cada disciplina
        |--------------------------------------------------------------------------
        */

        foreach ($turmas as &$turma) {

            $crawlerTurma = $browser->get(
                $turma['url']
            );

            $turma['professores'] =
                $professorScraper->extrair(
                    $crawlerTurma
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Salva disciplinas e professores no banco
        |--------------------------------------------------------------------------
        */

        foreach ($turmas as $disc) {

            $disciplina = Disciplina::updateOrCreate(
                [
                    'suap_id' => $disc['id']
                ],
                [
                    'codigo' => $disc['codigo'],
                    'nome'   => $disc['nome']
                ]
            );

            foreach ($disc['professores'] as $dadosProfessor) {

                $professor = Professor::updateOrCreate(
                    [
                        'matricula' =>
                            $dadosProfessor['matricula'] ?: null
                    ],
                    [
                        'nome' => $dadosProfessor['nome']
                    ]
                );

                $disciplina->professores()
                    ->syncWithoutDetaching([
                        $professor->id => [
                            'turma_codigo' => $codigoTurma
                        ]
                    ]);
            }
        }

        return "SYNC FINALIZADO COM SUCESSO";
    }
}
?>
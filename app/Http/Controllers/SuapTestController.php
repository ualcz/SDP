<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\Suap\Browser;
use App\Services\Suap\TurmaScraper;
use App\Services\Suap\ProfessorScraper;
use App\Services\Suap\TurmaAlunoScraper;

use App\Models\Disciplina;
use App\Models\Professor;

class SuapTestController extends Controller
{
    public function index(
        Browser $browser,
        TurmaScraper $turmaScraper,
        ProfessorScraper $professorScraper,
        TurmaAlunoScraper $turmaAlunoScraper
    ) {

        $usuario = Auth::user();

        $senha = Crypt::decryptString($usuario->senha_suap);

        $browser->login(
            $usuario->matricula,
            $senha
        );

        /*
        |--------------------------------------------------------------------------
        | Descobre o código da turma do aluno
        |--------------------------------------------------------------------------
        */

        $paginaAluno = $browser->get(
            "/edu/aluno/{$usuario->matricula}/"
        );

        $codigoTurma = $turmaAlunoScraper->codigoAtual(
            $paginaAluno
        );

        /*
|--------------------------------------------------------------------------
| Atualiza o código da turma do usuário
|--------------------------------------------------------------------------
*/

$usuario->update([
    'turma_codigo' => $codigoTurma
]);

        /*
        |--------------------------------------------------------------------------
        | Lista as disciplinas
        |--------------------------------------------------------------------------
        */

        $pagina = $browser->get('/edu/salas_virtuais/');

        $turmas = $turmaScraper->listar($pagina);

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
                $professorScraper->extrair($crawlerTurma);
        }

        /*
        |--------------------------------------------------------------------------
        | Salva tudo no banco
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

            foreach ($disc['professores'] as $nomeProfessor) {

                $professor = Professor::firstOrCreate([
                    'nome' => $nomeProfessor
                ]);

                $disciplina->professores()->syncWithoutDetaching([

                    $professor->id => [
                        'turma_codigo' => $codigoTurma
                    ]

                ]);
            }
        }

        return "SYNC FINALIZADO COM SUCESSO";
    }
}
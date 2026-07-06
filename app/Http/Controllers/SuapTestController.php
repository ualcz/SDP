<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\Suap\Browser;
use App\Services\Suap\TurmaScraper;
use App\Services\Suap\ProfessorScraper;
use App\Models\Disciplina;
use App\Models\Professor;

class SuapTestController extends Controller
{
    public function index(
        Browser $browser,
        TurmaScraper $turmaScraper,
        ProfessorScraper $professorScraper
    ) {
        $usuario = Auth::user();

        $senha = Crypt::decryptString($usuario->senha_suap);

        $browser->login(
            $usuario->matricula,
            $senha
        );

        // Página principal (já retorna Crawler)
        $pagina = $browser->get('/edu/salas_virtuais/');

        $turmas = $turmaScraper->listar($pagina);

        foreach ($turmas as &$turma) {

            $crawlerTurma = $browser->get($turma['url']);

            $turma['professores'] =
                $professorScraper->extrair($crawlerTurma);
        }

        // ❌ REMOVIDO dd($turmas);

        foreach ($turmas as $disc) {

            // 1. SALVAR DISCIPLINA
            $disciplina = Disciplina::updateOrCreate(
                ['suap_id' => $disc['id']],
                [
                    'codigo' => $disc['codigo'],
                    'nome' => $disc['nome']
                ]
            );

            // 2. SALVAR PROFESSORES + RELAÇÃO
            foreach ($disc['professores'] as $nomeProfessor) {

                $professor = Professor::firstOrCreate(
                    ['nome' => $nomeProfessor]
                );

                // 3. RELACIONAR (pivot)
                $disciplina->professores()
                    ->syncWithoutDetaching($professor->id);
            }
        }

        return "SYNC FINALIZADO COM SUCESSO";
    }
}
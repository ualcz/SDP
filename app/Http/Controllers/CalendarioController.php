<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use App\Models\DisciplinaProfessor;
use App\Models\Professor;

class CalendarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Abre a página do calendário
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $usuario = auth()->user();

        $ofertas = collect();
        $turmas = collect();

        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        if ($usuario->isProfessor()) {

            $professor = Professor::where(
                'matricula',
                $usuario->matricula
            )->first();

            if ($professor) {
                $ofertas = DisciplinaProfessor::with([
                    'disciplina',
                    'professor'
])
            ->where(
                'professor_id',
                $professor->id
)
->get();

                /*
                |--------------------------------------------------------------
                | Turmas nas quais o professor possui disciplinas
                |--------------------------------------------------------------
                */

                $turmas = $ofertas
                    ->pluck('turma_codigo')
                    ->filter()
                    ->unique()
                    ->values();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REPRESENTANTE
        |--------------------------------------------------------------------------
        */

        elseif ($usuario->representanteAtivo()) {
            $ofertas = DisciplinaProfessor::with([
                'disciplina',
                'professor'
            ])
            ->where(
                'turma_codigo',
                $usuario->turma_codigo
            )
            ->get();

            $turmas = collect([
                $usuario->turma_codigo
            ]);
        }

        return view('calendario.principal', [

            'ofertas' => $ofertas,

            'turmas' => $turmas,

            'podeGerenciarEventos' =>
                $usuario->isAdmin()
                || $usuario->isProfessor()
                || $usuario->representanteAtivo(),

            'podeExcluirEventos' =>
            $usuario->isAdmin()
            || $usuario->isProfessor()
            || $usuario->representanteAtivo(),

            'ehProfessor' =>
                $usuario->isProfessor(),

            'ehRepresentante' =>
                $usuario->representanteAtivo()
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Retorna eventos para o FullCalendar
    |--------------------------------------------------------------------------
    */
    public function eventos(Request $request)
    {
        $usuario = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($usuario->isAdmin()) {

            $eventos = Evento::all();
        }

        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        elseif ($usuario->isProfessor()) {

            $professor = Professor::where(
                'matricula',
                $usuario->matricula
            )->first();

            if (!$professor) {
                return response()->json([]);
            }

            /*
            |--------------------------------------------------------------
            | Turma selecionada pelo professor
            |--------------------------------------------------------------
            */

            $turmaCodigo = $request->query('turma_codigo');

            /*
            |--------------------------------------------------------------
            | Sem turma selecionada
            |--------------------------------------------------------------
            */

            if (!$turmaCodigo) {
                return response()->json([]);
            }

            /*
            |--------------------------------------------------------------
            | Verifica se o professor realmente possui disciplina
            | nessa turma.
            |--------------------------------------------------------------
            */

            $ofertasDoProfessor = DisciplinaProfessor::where(
                'professor_id',
                $professor->id
            )
            ->where(
                'turma_codigo',
                $turmaCodigo
            )
            ->pluck('id');

            if ($ofertasDoProfessor->isEmpty()) {
                return response()->json([]);
            }

            /*
            |--------------------------------------------------------------
            | Agora buscamos TODAS as disciplinas da turma.
            |
            | Não apenas as disciplinas do professor logado.
            |--------------------------------------------------------------
            */

            $ofertasDaTurma = DisciplinaProfessor::where(
                'turma_codigo',
                $turmaCodigo
            )->pluck('id');

            $eventos = Evento::whereIn(
                'disciplina_professor_id',
                $ofertasDaTurma
            )->get();
        }

        /*
        |--------------------------------------------------------------------------
        | ALUNO / REPRESENTANTE
        |--------------------------------------------------------------------------
        */

        else {

            $ofertas = DisciplinaProfessor::where(
                'turma_codigo',
                $usuario->turma_codigo
            )->pluck('id');

            $eventos = Evento::whereIn(
                'disciplina_professor_id',
                $ofertas
            )->get();
        }


        /*
        |--------------------------------------------------------------------------
        | CARREGA DISCIPLINA E PROFESSOR
        |--------------------------------------------------------------------------
        */

        $eventos = $eventos->load(
            'oferta.disciplina',
            'oferta.professor'
        );


        /*
        |--------------------------------------------------------------------------
        | Define quais eventos o professor pode editar
        |--------------------------------------------------------------------------
        */

        $professorLogado = null;

        if ($usuario->isProfessor()) {

            $professorLogado = Professor::where(
                'matricula',
                $usuario->matricula
            )->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Converte para FullCalendar
        |--------------------------------------------------------------------------
        */

        $dados = $eventos->map(function ($evento) use (
            $usuario,
            $professorLogado
        ) {

            $oferta = $evento->oferta;

            $podeEditar = false;
            $podeExcluir = false;

            /*
            |--------------------------------------------------------------
            | ADMIN pode editar
            |--------------------------------------------------------------
            */

            if ($usuario->isAdmin()) {

                $podeEditar = true;
                $podeExcluir = $podeEditar;
            }

            /*
            |--------------------------------------------------------------
            | Professor somente se o evento pertence à disciplina dele
            |--------------------------------------------------------------
            */

            elseif (
                $usuario->isProfessor()
                && $professorLogado
                && $oferta
                && $oferta->professor_id === $professorLogado->id
            ) {

                $podeEditar = true;
                $podeExcluir = $podeEditar;
            }

            /*
            |--------------------------------------------------------------
            | Representante pode editar eventos da própria turma
            |--------------------------------------------------------------
            */

            elseif (
                $usuario->representanteAtivo()
                && $oferta
                && $oferta->turma_codigo === $usuario->turma_codigo
            ) {

                $podeEditar = true;
                $podeExcluir = $podeEditar;
            }

            return [

                'id' => $evento->id,

                'title' => $evento->titulo,

                'start' => $evento->data_inicio,

                'color' => $this->corPorTipo(
                    $evento->tipo
                ),

                'extendedProps' => [

                    'tipo' => $evento->tipo,

                    'hora_inicio' =>
                        $evento->hora_inicio,

                    'hora_fim' =>
                        $evento->hora_fim,

                    'descricao' =>
                        $evento->descricao,

                    'data_inicio' =>
                        $evento->data_inicio,

                    'disciplina' =>
                        $oferta?->disciplina?->nome,

                    
                    'professor' =>
                        $oferta?->professor?->nome,
                    'professor_id' =>
                        $oferta?->professor_id,

                    'turma_codigo' =>
                        $oferta?->turma_codigo,

                    'disciplina_professor_id' =>
                        $evento->disciplina_professor_id,

                    /*
                    |------------------------------------------------------
                    | Informação usada pelo JavaScript
                    |------------------------------------------------------
                    */

                    'pode_editar' =>
                        $podeEditar,
                    'pode_excluir' =>
                        $podeExcluir
                ]
            ];
        });

        return response()->json($dados);
    }


    /*
    |--------------------------------------------------------------------------
    | Salva evento
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        if (!auth()->user()->podeGerenciarEventos()) {

            abort(
                403,
                'Você não possui permissão.'
            );
        }

        $request->validate([

            'titulo' =>
                'required',

            'tipo' =>
                'required',

            'data_inicio' =>
                'required|date',

            'disciplina_professor_id' =>
                'required|exists:disciplina_professor,id'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verifica se o usuário pode usar essa disciplina
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioPodeGerenciarOferta(
                $request->disciplina_professor_id
            )
        ) {

            abort(
                403,
                'Você não possui permissão para esta disciplina.'
            );
        }


        Evento::create([

            'titulo' =>
                $request->titulo,

            'tipo' =>
                $request->tipo,

            'data_inicio' =>
                $request->data_inicio,

            'hora_inicio' =>
                $request->hora_inicio,

            'hora_fim' =>
                $request->hora_fim,

            'descricao' =>
                $request->descricao,

            'disciplina_professor_id' =>
                $request->disciplina_professor_id
        ]);


        return response()->json([
            'success' => true
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Atualiza evento
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Evento $evento
    ) {

        /*
        |--------------------------------------------------------------------------
        | Verifica se o usuário pode editar ESTE evento
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioPodeGerenciarEvento(
                $evento
            )
        ) {

            abort(
                403,
                'Você não possui permissão para editar este evento.'
            );
        }


        $request->validate([

            'titulo' =>
                'required',

            'tipo' =>
                'required',

            'data_inicio' =>
                'required|date',

            'disciplina_professor_id' =>
                'required|exists:disciplina_professor,id'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verifica se pode utilizar a nova disciplina
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioPodeGerenciarOferta(
                $request->disciplina_professor_id
            )
        ) {

            abort(
                403,
                'Você não possui permissão para esta disciplina.'
            );
        }


        $evento->update([

            'titulo' =>
                $request->titulo,

            'tipo' =>
                $request->tipo,

            'data_inicio' =>
                $request->data_inicio,

            'hora_inicio' =>
                $request->hora_inicio,

            'hora_fim' =>
                $request->hora_fim,

            'descricao' =>
                $request->descricao,

            'disciplina_professor_id' =>
                $request->disciplina_professor_id
        ]);


        return response()->json([
            'success' => true
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Exclui evento
    |--------------------------------------------------------------------------
    */
    public function destroy(Evento $evento)
{
    $usuario = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Verifica se o usuário pode excluir ESTE evento
    |--------------------------------------------------------------------------
    */

    if (!$this->usuarioPodeGerenciarEvento($evento)) {

        abort(
            403,
            'Você não possui permissão para excluir este evento.'
        );
    }


    $evento->delete();


    return response()->json([
        'success' => true
    ]);
}


    /*
    |--------------------------------------------------------------------------
    | Verifica se usuário pode gerenciar uma oferta
    |--------------------------------------------------------------------------
    */
    private function usuarioPodeGerenciarOferta(
        int $ofertaId
    ): bool {

        $usuario = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($usuario->isAdmin()) {

            return true;
        }


        $oferta = DisciplinaProfessor::find(
            $ofertaId
        );


        if (!$oferta) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        if ($usuario->isProfessor()) {

            $professor = Professor::where(
                'matricula',
                $usuario->matricula
            )->first();

            if (!$professor) {

                return false;
            }

            return $oferta->professor_id
                === $professor->id;
        }


        /*
        |--------------------------------------------------------------------------
        | REPRESENTANTE
        |--------------------------------------------------------------------------
        */

        if ($usuario->representanteAtivo()) {

            return $oferta->turma_codigo
                === $usuario->turma_codigo;
        }


        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Verifica se pode editar um evento específico
    |--------------------------------------------------------------------------
    */
    private function usuarioPodeGerenciarEvento(
        Evento $evento
    ): bool {

        return $this->usuarioPodeGerenciarOferta(
            $evento->disciplina_professor_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Cor do evento
    |--------------------------------------------------------------------------
    */
    private function corPorTipo($tipo)
    {
        return match ($tipo) {

            'prova' =>
                '#FCA5A5',

            'trabalho' =>
                '#FDBA74',

            'seminario' =>
                '#C4B5FD',

            'reuniao' =>
                '#93C5FD',

            default =>
                '#86EFAC'
        };
    }
}
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
            'oferta.professor',
            'criador'
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

                'color' => $this->corEvento($evento),

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

                    'criador' =>
                        $evento->criador?->nome,
                    
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

/*
|--------------------------------------------------------------------------
| Verifica quantidade de eventos de uma turma em uma data
|--------------------------------------------------------------------------
*/
public function verificarLimite(Request $request)
{
    $usuario = auth()->user();

    $request->validate([
        'data' => 'required|date',
        'turma_codigo' => 'required|string',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Verifica se o usuário pode acessar essa turma
    |--------------------------------------------------------------------------
    */

    if ($usuario->isProfessor()) {

        $professor = Professor::where(
            'matricula',
            $usuario->matricula
        )->first();

        if (!$professor) {
            return response()->json([
                'permitido' => false,
                'mensagem' => 'Professor não encontrado.'
            ], 403);
        }

        $possuiTurma = DisciplinaProfessor::where(
            'professor_id',
            $professor->id
        )
        ->where(
            'turma_codigo',
            $request->turma_codigo
        )
        ->exists();

        if (!$possuiTurma) {
            return response()->json([
                'permitido' => false,
                'mensagem' => 'Você não possui acesso a esta turma.'
            ], 403);
        }
    }

    elseif ($usuario->representanteAtivo()) {

        if ($request->turma_codigo !== $usuario->turma_codigo) {
            return response()->json([
                'permitido' => false,
                'mensagem' => 'Você não possui acesso a esta turma.'
            ], 403);
        }
    }

    else {
        return response()->json([
            'permitido' => false,
            'mensagem' => 'Você não possui permissão.'
        ], 403);
    }

    /*
    |--------------------------------------------------------------------------
    | Busca todas as disciplinas daquela turma
    |--------------------------------------------------------------------------
    */

    $ofertas = DisciplinaProfessor::where(
        'turma_codigo',
        $request->turma_codigo
    )->pluck('id');

    /*
    |--------------------------------------------------------------------------
    | Conta os eventos da turma naquela data
    |--------------------------------------------------------------------------
    */

    $quantidade = Evento::whereIn(
        'disciplina_professor_id',
        $ofertas
    )
    ->whereDate(
        'data_inicio',
        $request->data
    )
    ->count();

    return response()->json([
        'quantidade' => $quantidade,
        'limite_atingido' => $quantidade >= 3
    ]);
}

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
    'required|date|after_or_equal:today',

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

        $oferta = DisciplinaProfessor::find(
            $request->disciplina_professor_id
        );
        
        $quantidade = Evento::whereIn(
            'disciplina_professor_id',
            DisciplinaProfessor::where(
                'turma_codigo',
                $oferta->turma_codigo
            )->pluck('id')
        )
        ->whereDate(
            'data_inicio',
            $request->data_inicio
        )
        ->count();
        
        if ($quantidade >= 3) {
        
            return response()->json([
                'limite_atingido' => true,
                'mensagem' =>
                    'Não é possível criar este evento, pois esta turma já possui 3 avaliações marcadas para esta data.'
            ], 422);
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
                $request->disciplina_professor_id,

            'criado_por' => auth()->id()
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

        $oferta = DisciplinaProfessor::find(
            $request->disciplina_professor_id
        );
        
        $quantidade = Evento::whereIn(
            'disciplina_professor_id',
            DisciplinaProfessor::where(
                'turma_codigo',
                $oferta->turma_codigo
            )->pluck('id')
        )
        ->whereDate(
            'data_inicio',
            $request->data_inicio
        )
        ->where(
            'id',
            '!=',
            $evento->id
        )
        ->count();
        
        if ($quantidade >= 3) {
        
            return response()->json([
                'limite_atingido' => true,
                'mensagem' =>
                    'Não é possível alterar a data deste evento, pois esta turma já possui 3 avaliações marcadas para essa data.'
            ], 422);
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
    private function corEvento($evento)
    {
        if (
            \Carbon\Carbon::parse(
                $evento->data_inicio
            )->isBefore(
                \Carbon\Carbon::today()
            )
        ) {
            return '#bfbfbf';
        }
    
        return $this->corPorTipo(
            $evento->tipo
        );
    }
    
    private function corPorTipo($tipo)
    {
        return match ($tipo) {
    
            'prova' =>
                '#E45756',
    
            'trabalho' =>
                '#D9825B',
    
            'seminario' =>
                '#D6A83D',
    
            'reuniao' =>
                '#53687A',
    
            default =>
                '#81758F'
        };
    }
}
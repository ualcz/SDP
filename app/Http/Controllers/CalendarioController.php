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

    if ($usuario->isProfessor()) {

        $professor = Professor::where(
            'matricula',
            $usuario->matricula
        )->first();

        if ($professor) {

            $ofertas = DisciplinaProfessor::with(
                'disciplina'
            )
            ->where(
                'professor_id',
                $professor->id
            )
            ->get();
        }
    }

    elseif ($usuario->representanteAtivo()) {

        $ofertas = DisciplinaProfessor::with(
            'disciplina'
        )
        ->where(
            'turma_codigo',
            $usuario->turma_codigo
        )
        ->get();
    }

    return view('calendario.principal', [

        'ofertas' => $ofertas,

        'podeGerenciarEventos' =>
            $usuario->isAdmin()
            || $usuario->isProfessor()
            || $usuario->representanteAtivo(),

        'podeExcluirEventos' =>
            $usuario->isAdmin()
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Retorna eventos para o FullCalendar
    |--------------------------------------------------------------------------
    */
    public function eventos()
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

        $ofertas = DisciplinaProfessor::where(
            'professor_id',
            $professor->id
        )->pluck('id');

        $eventos = Evento::whereIn(
            'disciplina_professor_id',
            $ofertas
        )->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ALUNO E REPRESENTANTE
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
    $eventos = $eventos->load(
    'oferta.disciplina',
    'oferta.professor'
);

    $dados = $eventos->map(function ($evento) {

    $oferta = $evento->oferta;

    return [

        'id' => $evento->id,

        'title' => $evento->titulo,

        'start' => $evento->data_inicio,

        'color' => $this->corPorTipo(
            $evento->tipo
        ),

        'extendedProps' => [

            'tipo' => $evento->tipo,

            'hora_inicio' => $evento->hora_inicio,

            'hora_fim' => $evento->hora_fim,

            'descricao' => $evento->descricao,

            'data_inicio' => $evento->data_inicio,

            'disciplina' =>
                $oferta?->disciplina?->nome,

            'professor' =>
                $oferta?->professor?->nome,

            'disciplina_professor_id' =>
                $evento->disciplina_professor_id
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

    'titulo' => 'required',

    'tipo' => 'required',

    'data_inicio' => 'required|date',

    'disciplina_professor_id' =>
        'required|exists:disciplina_professor,id'
]);

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

    'titulo' => $request->titulo,

    'tipo' => $request->tipo,

    'data_inicio' => $request->data_inicio,

    'hora_inicio' => $request->hora_inicio,

    'hora_fim' => $request->hora_fim,

    'descricao' => $request->descricao,

    'disciplina_professor_id' =>
        $request->disciplina_professor_id
]);
    }

    /*
    |--------------------------------------------------------------------------
    | Atualiza evento
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Evento $evento)
    {
        if (
    !$this->usuarioPodeGerenciarEvento(
        $evento
    )
) {
    abort(403);
}
        if (!auth()->user()->podeGerenciarEventos()) {

        abort(
            403,
            'Você não possui permissão.'
        );
    }
    $request->validate([

    'titulo' => 'required',

    'tipo' => 'required',

    'data_inicio' => 'required|date',

    'disciplina_professor_id' =>
        'required|exists:disciplina_professor,id'
]);
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

         'titulo' => $request->titulo,

         'tipo' => $request->tipo,

        'data_inicio' => $request->data_inicio,

        'hora_inicio' => $request->hora_inicio,

        'hora_fim' => $request->hora_fim,

        'descricao' => $request->descricao,

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
        if (!auth()->user()->podeExcluirEventos()) {

        abort(
            403,
            'Você não possui permissão.'
        );
    }
        $evento->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Define cor do evento
    |--------------------------------------------------------------------------
    */
    private function usuarioPodeGerenciarOferta(
    int $ofertaId
): bool
{
    $usuario = auth()->user();

    /*
    |------------------------------------------------------------------
    | ADMIN
    |------------------------------------------------------------------
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
    |------------------------------------------------------------------
    | PROFESSOR
    |------------------------------------------------------------------
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
    |------------------------------------------------------------------
    | REPRESENTANTE
    |------------------------------------------------------------------
    */

    if ($usuario->representanteAtivo()) {

        return $oferta->turma_codigo
            === $usuario->turma_codigo;
    }

    return false;
}

private function usuarioPodeGerenciarEvento(
    Evento $evento
): bool
{
    return $this->usuarioPodeGerenciarOferta(
        $evento->disciplina_professor_id
    );
}
    private function corPorTipo($tipo)
    {
        return match ($tipo) {

            'prova' => '#FCA5A5',

            'trabalho' => '#FDBA74',

            'seminario' => '#C4B5FD',

            'reuniao' => '#93C5FD',

            default => '#86EFAC'
        };
    }
}
?>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class CalendarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Abre a página do calendário
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('calendario.principal');
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna eventos para o FullCalendar
    |--------------------------------------------------------------------------
    */
    public function eventos()
    {
        $eventos = Evento::all();

        $dados = $eventos->map(function ($evento) {

            return [
                'id' => $evento->id,

                'title' => $evento->titulo,

                'start' => $evento->data_inicio,

                'color' => $this->corPorTipo($evento->tipo),

                'extendedProps' => [
                    'tipo' => $evento->tipo,
                    'hora_inicio' => $evento->hora_inicio,
                    'hora_fim' => $evento->hora_fim,
                    'descricao' => $evento->descricao
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
        $request->validate([
            'titulo' => 'required',
            'tipo' => 'required',
            'data_inicio' => 'required|date'
        ]);

        Evento::create([
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'data_inicio' => $request->data_inicio,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'descricao' => $request->descricao
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
    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'titulo' => 'required',
            'tipo' => 'required',
            'data_inicio' => 'required|date'
        ]);

        $evento->update([
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'data_inicio' => $request->data_inicio,
            'hora_inicio' => $request->hora_inicio,
            'hora_fim' => $request->hora_fim,
            'descricao' => $request->descricao
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
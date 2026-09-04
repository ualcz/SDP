<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requerimento;
use Illuminate\Support\Facades\Auth;

class RequerimentoController extends Controller
{
    public function create(Request $request) {
        $setores = config('setores.destinatarios', []);
        $modelos = config('modelos_requerimentos.modelos', []);

        // Modelo selecionado via query string (padrão: cores)
        $modeloChave = $request->query('modelo', 'cores');

        if (!isset($modelos[$modeloChave])) {
            $modeloChave = !empty($modelos) ? array_key_first($modelos) : 'cores';
        }

        $modeloAtivo = $modelos[$modeloChave] ?? null;

        // Setor de destino do modelo ativo
        $setorChave = $modeloAtivo['setor_chave'] ?? 'teste1';
        $setorDestino = $setores[$setorChave] ?? [
            'nome' => $modeloAtivo['setor_nome'] ?? 'Setor Responsável',
            'email' => $modeloAtivo['email'] ?? 'protocolos.seabra@ifba.edu.br',
        ];

        return view('requerimentos.form', compact('modelos', 'modeloChave', 'modeloAtivo', 'setores', 'setorChave', 'setorDestino'));
    }

    //Método para mostrar requerimentos que já foram realizados pelo usuário;
    public function index(Request $request)
    {
        $requerimentos = Requerimento::all();
        return view('requerimentos.meusRequerimentos', compact('requerimentos'));  
    }
}

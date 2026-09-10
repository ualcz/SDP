<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requerimento;
use App\Models\Setor;
use Illuminate\Support\Facades\Auth;

class RequerimentoController extends Controller
{
    public function create(Request $request) {
        $setores = config('setores.destinatarios', []);
        $modelos = Setor::obterSetoresFormatados();

        // Setor / Modelo selecionado via query string (padrão: cores)
        $modeloChave = $request->query('setor', $request->query('modelo', 'cores'));

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
        //Implementação da lógica de que o usuário logado só pode ver os seus próprios requerimentos;
        //Uso de chave estrangeira na tabela requerimentos;
        $query = auth()->user()->requerimentos();
        
        if ($request->filled('objetoDoRequerimento')) {
            $query->where('objetoDoRequerimento', 'LIKE', '%' . $request->input('objetoDoRequerimento') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', 'LIKE', '%' . $request->input('status') . '%');
        }

        $requerimentos = $query->get();
        return view('requerimentos.meusRequerimentos', compact('requerimentos'));  
    }
}

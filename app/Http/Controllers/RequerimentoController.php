<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setor;

class RequerimentoController extends Controller
{
    public function create(Request $request) {
        $modelos = Setor::obterSetoresFormatados();

        // Setor selecionado via query string (id ou sigla)
        $setorParam = $request->query('setor', $request->query('modelo'));
        $modeloAtivo = null;

        if ($setorParam) {
            if (isset($modelos[$setorParam])) {
                $modeloAtivo = $modelos[$setorParam];
            } else {
                foreach ($modelos as $mod) {
                    if (strcasecmp($mod['setor_sigla'], $setorParam) === 0) {
                        $modeloAtivo = $mod;
                        break;
                    }
                }
            }
        }

        if (!$modeloAtivo) {
            $modeloAtivo = !empty($modelos) ? reset($modelos) : null;
        }

        $modeloChave = $modeloAtivo['id'] ?? null;
        $setorDestino = [
            'nome' => $modeloAtivo['setor_nome'] ?? 'Setor Responsável',
            'email' => $modeloAtivo['email'] ?? 'protocolos.seabra@ifba.edu.br',
        ];

        return view('requerimentos.form', compact('modelos', 'modeloChave', 'modeloAtivo', 'setorDestino'));
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
        if ($request->filled('numero_protocolo')) {
            $query->where('numero_protocolo', 'LIKE', '%' . $request->input('numero_protocolo') . '%');
        }

        $requerimentos = $query->get();
        return view('requerimentos.meusRequerimentos', compact('requerimentos'));  
    }
}

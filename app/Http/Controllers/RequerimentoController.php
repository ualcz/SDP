<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requerimento;
use Illuminate\Support\Facades\Auth;

class RequerimentoController extends Controller
{
    public function create() {
        $setores = config('setores.destinatarios', []);
        return view('requerimentos.form', compact('setores'));
    }

    //Método para mostrar requerimentos que já foram realizados pelo usuário;
    public function index(Request $request)
    {
        $user = Auth::user()->name;
        $requerimentos = Requerimento::where('objetoDoRequerimento','motivo')->get();
        return view('requerimentos.meusRequerimentos', compact('requerimentos'));  
    }
}

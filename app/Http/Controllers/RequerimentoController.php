<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequerimentoController extends Controller
{
    public function create() {
        $setores = config('setores.destinatarios', []);
        return view('requerimentos.form', compact('setores'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequerimentoController extends Controller
{
    public function create() {
        return view('requerimentos.form');
    }
}

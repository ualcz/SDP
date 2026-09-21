<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requerimento;
use App\Models\Setor;


class ResponsavelSetorController extends Controller
{
    public function index($id)
    {
        $usuario = auth()->user();
        $setor = Setor::findOrFail($id);

       return view('setor.responsavel.dashboard', compact('setor'));
    }
}

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turma;

class TurmaController extends Controller
{
    public function listar()
{
    $turmas = Turma::all();

    return view('admin.turmas.listar', compact('turmas'));
}
    public function create()
    {
        return view('admin.turmas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required'
        ]);

        Turma::create([
            'nome' => $request->nome,
            'codigo_acesso' => strtoupper(uniqid())
        ]);

        return back()->with('success', 'Turma criada com sucesso!');
    }
}
?>
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turma;

class TurmaController extends Controller
{
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

        return redirect('/admin/dashboard');
    }
}
?>
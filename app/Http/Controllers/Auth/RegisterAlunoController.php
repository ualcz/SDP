<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Support\Facades\Hash;

class RegisterAlunoController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6|confirmed',
            'matricula' => 'required',
            'codigo_turma' => 'required'
        ]);

        // 🔎 valida código da turma
        $turma = Turma::where('codigo_acesso', $request->codigo_turma)->first();

        if (!$turma) {
            return back()->withErrors(['codigo_turma' => 'Código inválido']);
        }

        // 👤 cria usuário
        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'aluno'
        ]);

        // 🎓 cria aluno vinculado
        Aluno::create([
            'usuario_id' => $usuario->id,
            'matricula' => $request->matricula,
            'turma_id' => $turma->id
        ]);

        return redirect('/login/aluno');
    }
}
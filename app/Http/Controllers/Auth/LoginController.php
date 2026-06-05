<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return back()->withErrors(['login' => 'Credenciais inválidas']);
        }

        $user = Auth::user();

        // 🔐 valida role escolhido na tela
        if ($user->role !== $request->role) {
            Auth::logout();
            return back()->withErrors(['login' => 'Perfil incorreto']);
        }

        return match ($user->role) {
            'aluno' => redirect('/aluno/dashboard'),
            'professor' => redirect('/professor/dashboard'),
            'admin' => redirect('/admin/dashboard'),
            'representante' => redirect('/representante/dashboard'),
        };
    }
}
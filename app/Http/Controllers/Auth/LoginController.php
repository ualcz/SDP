<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // falar com o banco de dados sobre o ALUNO

public function loginAluno(Request $request)
{
    
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['login' => 'Email ou senha inválidos.']);
    }

    if (Auth::user()->role !== 'aluno') {
        Auth::logout();
        return back()->withErrors(['login' => 'Este usuário não é aluno.']);
    }

    return redirect('/aluno/dashboard');
}

// falar com o banco de dados sobre o PROFESSOR
public function loginProfessor(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['login' => 'Email ou senha inválidos.']);
    }

    if (Auth::user()->role !== 'professor') {
        Auth::logout();
        return back()->withErrors(['login' => 'Este usuário não é professor.']);
    }

    return redirect('/professor/dashboard');
}

// falar com o banco de dados sobre o ADMIN
public function loginAdmin(Request $request)
{
    
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['login' => 'Email ou senha inválidos.']);
    }

    if (Auth::user()->role !== 'admin') {
        Auth::logout();
        return back()->withErrors(['login' => 'Este usuário não é admin.']);
    }

    return redirect('/admin/dashboard');
}

// falar com o banco de dados sobre o REPRESENTANTE
public function loginRepresentante(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['login' => 'Email ou senha inválidos.']);
    }

    if (Auth::user()->role !== 'representante') {
        Auth::logout();
        return back()->withErrors(['login' => 'Este usuário não é representante.']);
    }

    return redirect('/representante/dashboard');
}
}
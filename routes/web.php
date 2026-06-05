<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterAlunoController;

// 🧭 escolha de perfil
Route::view('/', 'auth.select-role');

// 🔐 LOGIN
Route::post('/login', [LoginController::class, 'login']);

// 🎓 CADASTRO ALUNO
Route::get('/register/aluno', function () {
    return view('auth.aluno-register');
});

Route::post('/register/aluno', [RegisterAlunoController::class, 'register']);

// 🖥️ views login
Route::view('/login/aluno', 'auth.aluno-login');
Route::view('/login/professor', 'auth.professor-login');
Route::view('/login/admin', 'auth.admin-login');
Route::view('/login/representante', 'auth.representante-login');
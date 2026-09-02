<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| REDIRECIONAMENTO INICIAL
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| LOGIN & AUTENTICAÇÃO
|--------------------------------------------------------------------------
| Admin: email + senha local
| Aluno / Servidor: matrícula + senha SUAP
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PAINEL / REQUERIMENTOS - ALUNO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:aluno'])->group(function () {
    Route::get('/requerimentos/aluno', function () {
        return view('requerimentos.aluno');
    })->name('requerimentos.aluno');
});

/*
|--------------------------------------------------------------------------
| PAINEL / REQUERIMENTOS - SERVIDOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:professor,admin'])->group(function () {
    Route::get('/requerimentos/servidor', function () {
        return view('requerimentos.servidor');
    })->name('requerimentos.servidor');
});
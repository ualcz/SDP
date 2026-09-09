<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EnvioEmailController;
use App\Http\Controllers\RequerimentoController;
use App\Http\Controllers\RequerimentoPdfController;

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
        $setores = config('setores.destinatarios', []);
        return view('requerimentos.aluno', compact('setores'));
    })->name('requerimentos.aluno');

    Route::get('/requerimentos/aluno/enviar-email', function () {
        return redirect()->route('requerimentos.aluno.novo');
    });
    Route::post('/requerimentos/aluno/enviar-email', [EnvioEmailController::class, 'enviar'])->name('aluno.enviar-email');
    Route::get('/requerimentos/aluno/novo', [RequerimentoController::class, 'create'])->name('requerimentos.aluno.novo');
    Route::get('/requerimentos/aluno/meusRequerimentos', [RequerimentoController::class, 'index'])->name('requerimentos.aluno.meusRequerimentos');
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

/*
|--------------------------------------------------------------------------
| VISUALIZAÇÃO DE BLADE & GERAÇÃO DE PDF
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/requerimentos/visualizar-blade', [RequerimentoPdfController::class, 'visualizarBlade'])->name('requerimentos.visualizar-blade');
    Route::get('/requerimentos/gerar-pdf', [RequerimentoPdfController::class, 'gerarPdf'])->name('requerimentos.gerar-pdf');
    Route::get('/requerimentos/{id}/gerar-comprovante', [RequerimentoPdfController::class, 'gerarComprovante'])->name('requerimentos.gerar-comprovante');
});


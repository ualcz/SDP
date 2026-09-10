<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EnvioEmailController;
use App\Http\Controllers\RequerimentoController;
use App\Http\Controllers\RequerimentoPdfController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminConsultaController;
use App\Http\Controllers\AdminModeloController;

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
| PAINEL ADMINISTRATIVO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/consultar-requerimentos', [AdminConsultaController::class, 'index'])
        ->name('admin.consultar-requerimentos');


    // Gerenciamento de Modelos e Assuntos de Requerimentos
    Route::get('/admin/modelos', [AdminModeloController::class, 'index'])->name('admin.modelos.index');
    Route::get('/admin/modelos/{id}/editar', [AdminModeloController::class, 'edit'])->name('admin.modelos.edit');
    Route::put('/admin/modelos/{id}', [AdminModeloController::class, 'update'])->name('admin.modelos.update');
    Route::post('/admin/modelos/{id}/assuntos', [AdminModeloController::class, 'storeAssunto'])->name('admin.modelos.assuntos.store');
    Route::put('/admin/assuntos/{id}', [AdminModeloController::class, 'updateAssunto'])->name('admin.assuntos.update');
    Route::delete('/admin/assuntos/{id}', [AdminModeloController::class, 'destroyAssunto'])->name('admin.assuntos.destroy');

});

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


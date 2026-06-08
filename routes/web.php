<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterAlunoController;
use App\Http\Controllers\Admin\AlunoController;
use App\Http\Controllers\Admin\ProfessorController;
use App\Http\Controllers\Admin\TurmaController;

/*
|--------------------------------------------------------------------------
| TELA INICIAL
|--------------------------------------------------------------------------
| Aqui o usuário escolhe qual tipo de login ele quer acessar
*/
Route::view('/', 'auth.select-role');


/*
|--------------------------------------------------------------------------
| LOGIN ALUNO
|--------------------------------------------------------------------------
| Exibe tela de login e processa autenticação do aluno
*/
Route::view('/login/aluno', 'auth.aluno-login');

Route::post('/login/aluno', [LoginController::class, 'loginAluno']);


/*
|--------------------------------------------------------------------------
| LOGIN PROFESSOR
|--------------------------------------------------------------------------
*/
Route::view('/login/professor', 'auth.professor-login');

Route::post('/login/professor', [LoginController::class, 'loginProfessor']);


/*
|--------------------------------------------------------------------------
| LOGIN ADMIN
|--------------------------------------------------------------------------
*/
Route::view('/login/admin', 'auth.admin-login');

Route::post('/login/admin', [LoginController::class, 'loginAdmin']);


/*
|--------------------------------------------------------------------------
| LOGIN REPRESENTANTE
|--------------------------------------------------------------------------
*/
Route::view('/login/representante', 'auth.representante-login');

Route::post('/login/representante', [LoginController::class, 'loginRepresentante']);


/*
|--------------------------------------------------------------------------
| CADASTRO ALUNO
|--------------------------------------------------------------------------
| Apenas aluno se cadastra sozinho no sistema
*/
Route::view('/register/aluno', 'auth.aluno-register');

Route::post('/register/aluno', [RegisterAlunoController::class, 'register']);


/*
|--------------------------------------------------------------------------
| DASHBOARDS (PROTEGIDOS POR AUTH + ROLE)
|--------------------------------------------------------------------------
| Aqui está a parte correta: apenas usuários autenticados
| com role correspondente podem acessar
*/

Route::middleware(['auth', 'role:aluno'])->group(function () {
    Route::get('/aluno/dashboard', fn () => view('aluno.dashboard'));
});

Route::middleware(['auth', 'role:professor'])->group(function () {
    Route::get('/professor/dashboard', fn () => view('professor.dashboard'));
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {


Route::view(
    '/representante/dashboard',
    'representante.dashboard'
);

// ========================================
// Calendário
// ========================================
Route::view(
    '/calendario/principal',
    'calendario.principal'
);
    Route::get('/dashboard', fn () => view('admin.dashboard'));

    // TURMAS
    Route::get('/turmas/create', [TurmaController::class, 'create']);
    Route::post('/turmas', [TurmaController::class, 'store']);

    // PROFESSORES
    Route::get('/professores/create', [ProfessorController::class, 'create']);
    Route::post('/professores', [ProfessorController::class, 'store']);

    // PROMOVER ALUNO
    Route::get('/alunos/promover', [AlunoController::class, 'createPromocao']);
    Route::post('/alunos/promover', [AlunoController::class, 'storePromocao']);
});

Route::middleware(['auth', 'role:representante'])->group(function () {
    Route::get('/representante/dashboard', fn () => view('representante.dashboard'));
});

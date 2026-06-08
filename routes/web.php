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
*/
Route::view('/', 'auth.select-role');


/*
|--------------------------------------------------------------------------
| LOGIN ALUNO
|--------------------------------------------------------------------------
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
*/
Route::view('/register/aluno', 'auth.aluno-register');
Route::post('/register/aluno', [RegisterAlunoController::class, 'register']);


/*
|--------------------------------------------------------------------------
| DASHBOARDS - ALUNO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:aluno'])->group(function () {
    Route::get('/aluno/dashboard', fn () => view('aluno.dashboard'));
});


/*
|--------------------------------------------------------------------------
| DASHBOARDS - PROFESSOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:professor'])->group(function () {
    Route::get('/professor/dashboard', fn () => view('professor.dashboard'));
});


/*
|--------------------------------------------------------------------------
| DASHBOARD + ÁREA ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        |-------------------------
        | ADMIN DASHBOARD
        |-------------------------
        */
        Route::get('/dashboard', fn () => view('admin.dashboard'));


        /*
        |-------------------------
        | TURMAS
        |-------------------------
        */
        Route::get('/turmas/create', [TurmaController::class, 'create']);
        Route::post('/turmas', [TurmaController::class, 'store']);
        Route::get('/turmas/listar', [TurmaController::class, 'listar']);


        /*
        |-------------------------
        | PROFESSORES
        |-------------------------
        */
        Route::get('/professores/create', [ProfessorController::class, 'create']);
        Route::post('/professores', [ProfessorController::class, 'store']);


        /*
        |-------------------------
        | ALUNOS (PROMOÇÃO)
        |-------------------------
        */
        Route::get('/alunos/promover', [AlunoController::class, 'createPromocao']);
        Route::post('/alunos/promover', [AlunoController::class, 'storePromocao']);
    });


/*
|--------------------------------------------------------------------------
| CALENDÁRIO (TESTE - SEM MIDDLEWARE)
|--------------------------------------------------------------------------
*/
Route::view(
    '/calendario/principal',
    'calendario.principal'
);


/*
|--------------------------------------------------------------------------
| DASHBOARD - REPRESENTANTE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:representante'])->group(function () {
    Route::get('/representante/dashboard', fn () => view('representante.dashboard'));
});
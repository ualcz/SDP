<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterAlunoController;

// ========================================
// TELA INICIAL
// ========================================

Route::view('/', 'auth.select-role');


// ========================================
// LOGIN ALUNO
// ========================================

Route::view('/login/aluno', 'auth.aluno-login');

Route::post(
    '/login/aluno',
    [LoginController::class, 'loginAluno']
);


// ========================================
// LOGIN PROFESSOR
// ========================================

Route::view('/login/professor', 'auth.professor-login');

Route::post(
    '/login/professor',
    [LoginController::class, 'loginProfessor']
);


// ========================================
// LOGIN ADMIN
// ========================================

Route::view('/login/admin', 'auth.admin-login');

Route::post(
    '/login/admin',
    [LoginController::class, 'loginAdmin']
);


// ========================================
// LOGIN REPRESENTANTE
// ========================================

Route::view('/login/representante', 'auth.representante-login');

Route::post(
    '/login/representante',
    [LoginController::class, 'loginRepresentante']
);


// ========================================
// CADASTRO ALUNO
// ========================================

Route::view(
    '/register/aluno',
    'auth.aluno-register'
);

Route::post(
    '/register/aluno',
    [RegisterAlunoController::class, 'register']
);


// ========================================
// DASHBOARDS
// ========================================

Route::view(
    '/aluno/dashboard',
    'aluno.dashboard'
);

Route::view(
    '/professor/dashboard',
    'professor.dashboard'
);

Route::view(
    '/admin/dashboard',
    'admin.dashboard'
);

Route::view(
    '/representante/dashboard',
    'representante.dashboard'
);
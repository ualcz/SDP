<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AlunoController;
use App\Http\Controllers\Admin\ProfessorController;
use App\Http\Controllers\Admin\TurmaController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\SuapCrawlerController;
use App\Http\Controllers\SuapExplorerController;
use App\Http\Controllers\SuapTestController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| REDIRECIONAMENTO INICIAL
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

Route::get('/calendario/verificar-limite', [
    CalendarioController::class,
    'verificarLimite'
]);

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
| Admin: email + senha local
| Aluno/Professor: matrícula + senha SUAP
*/
Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout']);


/*
|--------------------------------------------------------------------------
| DASHBOARD - ALUNO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:aluno'])->group(function () {

    Route::get('/aluno/dashboard', function () {
        return view('aluno.dashboard');
    });

});


/*
|--------------------------------------------------------------------------
| DASHBOARD - PROFESSOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:professor'])->group(function () {

    Route::get('/professor/dashboard', function () {
        return view('professor.dashboard');
    });

});


/*
|--------------------------------------------------------------------------
| DASHBOARD - REPRESENTANTE
|--------------------------------------------------------------------------
|
| POR ENQUANTO:
| continua usando role:representante.
|
| FUTURAMENTE:
| trocar por middleware próprio consultando a tabela representantes.
|
*/
Route::middleware(['auth', 'representante'])->group(function () {

    Route::get('/representante/dashboard', function () {
        return view('representante.dashboard');
    });

});


/*
|--------------------------------------------------------------------------
| ÁREA ADMINISTRATIVA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        });


        /*
        |--------------------------------------------------------------------------
        | TURMAS
        |--------------------------------------------------------------------------
        */
        Route::get('/turmas/create', [TurmaController::class, 'create']);

        Route::post('/turmas', [TurmaController::class, 'store']);

        Route::get('/turmas/listar', [TurmaController::class, 'listar']);


        /*
        |--------------------------------------------------------------------------
        | PROFESSORES
        |--------------------------------------------------------------------------
        |
        | Futuramente essas rotas poderão ser removidas,
        | caso os professores sejam sincronizados pelo SUAP.
        |
        */
        Route::get('/professores/create', [ProfessorController::class, 'create']);

        Route::post('/professores', [ProfessorController::class, 'store']);


        /*
        |--------------------------------------------------------------------------
        | REPRESENTANTES
        |--------------------------------------------------------------------------
        |
        | Atualmente:
        | promoção de aluno.
        |
        | Futuramente:
        | registro na tabela representantes.
        |
        */
        Route::get('/alunos/promover', [AlunoController::class, 'createPromocao']);

        Route::post('/alunos/promover', [AlunoController::class, 'storePromocao']);

    });


/*
|--------------------------------------------------------------------------
| CALENDÁRIO
|--------------------------------------------------------------------------
|
| Sem middleware por enquanto (modo teste).
|
*/
Route::get('/calendario', [CalendarioController::class, 'index']);

Route::get('/eventos', [CalendarioController::class, 'eventos']);

Route::post('/eventos', [CalendarioController::class, 'store']);

Route::put('/eventos/{evento}', [CalendarioController::class, 'update']);

Route::delete('/eventos/{evento}', [CalendarioController::class, 'destroy']);

//rota teste para email
Route::get('/teste-email', function () {
    Mail::raw('Este é um teste de envio de e-mail do SCAAE. Teste número 2', function ($message) {
        $message->to('senhormu12q@gmail.com')
                ->subject('Teste SCAAE');
    });

    return 'E-mail enviado!';
});

Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get(
            '/suap/explorador',
            [SuapExplorerController::class,'index']
        );

        Route::post(
            '/suap/explorador',
            [SuapExplorerController::class,'consultar']
        );
});
Route::get('/teste-suap', [SuapTestController::class, 'index'])
    ->name('suap.sync');
?>
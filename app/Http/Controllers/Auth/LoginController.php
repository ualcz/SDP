<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\Usuario;
use App\Services\SuapService;
use App\Services\Suap\SuapSyncService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login único
    |--------------------------------------------------------------------------
    | Admin: email + senha local
    | Aluno/Professor: matrícula + senha SUAP
    */
    public function login(Request $request, SuapService $suap, SuapSyncService $syncService)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login = $request->login;
        $password = $request->password;

        /*
        |--------------------------------------------------------------------------
        | ADMIN LOCAL
        |--------------------------------------------------------------------------
        */
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            if (!Auth::attempt([
                'email' => $login,
                'password' => $password,
            ])) {

                return back()->withErrors([
                    'login' => 'Email ou senha inválidos.',
                ]);
            }

            return redirect('/admin/dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | SUAP
        |--------------------------------------------------------------------------
        */
        $jwt = $suap->autenticar($login, $password);

/*
|--------------------------------------------------------------------------
| FALLBACK LOCAL
|--------------------------------------------------------------------------
*/
if (!$jwt) {

    $usuario = Usuario::where('matricula', $login)->first();

    if (
        !$usuario ||
        !Hash::check($password, $usuario->password)
    ) {

        return back()->withErrors([
            'login' => 'Matrícula ou senha inválidos.',
        ]);
    }

    Auth::login($usuario);

    if ($usuario->isProfessor()) {
        return redirect('/requerimentos/servidor');
    }

    return redirect('/requerimentos/aluno');
}

        $dados = $suap->meusDados($jwt);

        if (!$dados) {

            return back()->withErrors([
                'login' => 'Não foi possível obter dados do SUAP.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PAPEL DO USUÁRIO
        |--------------------------------------------------------------------------
        */
        $role = 'aluno';

        if (($dados['tipo_vinculo'] ?? '') === 'Servidor') {
            $role = 'professor';
        }

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        | Alguns usuários do SUAP vêm com email vazio.
        | Nesse caso, usamos um email técnico baseado na matrícula.
        */
        $email = !empty($dados['email'])
            ? $dados['email']
            : $dados['matricula'] . '@ifba.edu.br';

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO LOCAL
        |--------------------------------------------------------------------------
        */
        $usuario = Usuario::updateOrCreate(

            [
                'matricula' => $dados['matricula'],
            ],
        
            [
                'nome' => $dados['nome_usual']
                    ?? $dados['vinculo']['nome']
                    ?? 'Usuário SUAP',
        
                'email' => $email,
        
                'password' => Hash::make($password),
        
                'senha_suap' => Crypt::encryptString($password),
        
                'role' => $role,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | LOGIN NO LARAVEL
        |--------------------------------------------------------------------------
        */
        Auth::login($usuario);

        /*
        |--------------------------------------------------------------------------
        | WEB SCRAPING: E-MAIL PESSOAL & TURMA
        |--------------------------------------------------------------------------
        | Busca dados complementares não disponíveis na API REST
        */
        $syncService->sincronizar($usuario, $password);

        /*
        |--------------------------------------------------------------------------
        | SALVA JWT NA SESSÃO
        |--------------------------------------------------------------------------
        | Permitirá futuras integrações com o SUAP
        | sem pedir a senha novamente.
        */
        session([
            'suap_jwt' => $jwt,
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECIONAMENTO
        |--------------------------------------------------------------------------
        */
        if ($usuario->isProfessor()) {

            return redirect('/requerimentos/servidor');
        }

        return redirect('/requerimentos/aluno');
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    public function logout()
    {
        Auth::logout();

        session()->forget('suap_jwt');

        return redirect('/login');
    }
}
?>
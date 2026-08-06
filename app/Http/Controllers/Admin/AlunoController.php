<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Usuario;
use App\Models\Representante;

class AlunoController extends Controller
{
    // 📄 abre a página
    public function createPromocao()
    {
        return view('admin.alunos.promover');
    }

    // ⚙ executa promoção
    public function storePromocao(Request $request)
    {
        $request->validate([
            'matricula' => 'required'
        ]);

        $aluno = Usuario::where('matricula', $request->matricula)->first();

        if (!$aluno) {
            return back()->withErrors([
                'matricula' => 'Aluno não encontrado'
            ]);
        }

        $usuario = Usuario::find($aluno->id);

        if (!$usuario) {
            return back()->withErrors([
                'matricula' => 'Usuário não encontrado'
            ]);
        }

Representante::updateOrCreate(

    [
        'usuario_id' => $usuario->id
    ],

    [
        'ativo' => true,
        'inicio_mandato' => now()
    ]
);
    }
}
?>
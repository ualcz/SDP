<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
    public function create()
    {
        return view('admin.professores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'professor'
        ]);

        return redirect('/admin/dashboard');
    }
}
?>
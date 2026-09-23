<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsersController extends Controller
{
    public function index(){
        $usuarios = Usuario::where('role', '!=', 'aluno')->get();
        return view('admin.users.index', compact('usuarios'));
    }
}

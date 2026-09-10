<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminConsultaController extends Controller
{
    public function index(){
        return view('admin.consultaRequerimento');
    }
}

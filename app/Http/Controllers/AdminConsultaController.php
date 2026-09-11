<?php

namespace App\Http\Controllers;


class AdminConsultaController extends Controller
{
    public function index(){
        return view('admin.consultaRequerimento');
    }
}

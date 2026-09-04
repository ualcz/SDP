<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    //Indicação dos campos que poderão ser preenchidos;
    protected $fillable = ['objetoDoRequerimento','motivo'];
}

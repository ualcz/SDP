<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    //Indicação dos campos que poderão ser preenchidos;
    protected $fillable = ['objetoDoRequerimento', 'motivo', 'situação'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

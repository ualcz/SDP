<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requerimentos', function (Blueprint $table) {
            $table->id();
            $table->string('objetoDoRequerimento');
            $table->string('motivo');
            //O campo 'situação' pode ser null, pois tem o valor padrão 'Em análise'.
            $table->string('situação')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requerimentos');
    }
};

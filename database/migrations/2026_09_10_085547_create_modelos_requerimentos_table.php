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
        Schema::create('modelos_requerimentos', function (Blueprint $table) {
            $table->id();
            $table->string('identificador')->unique();
            $table->string('setor_chave');
            $table->string('setor_sigla');
            $table->string('setor_nome');
            $table->string('email')->nullable();
            $table->string('titulo');
            $table->string('processo_prefixo')->default('23720');
            $table->string('rodape_contato')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelos_requerimentos');
    }
};

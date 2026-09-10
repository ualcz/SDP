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
        Schema::create('assuntos_requerimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modelo_requerimento_id')->constrained('modelos_requerimentos')->cascadeOnDelete();
            $table->string('codigo')->nullable();
            $table->string('descricao');
            $table->text('observacao')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assuntos_requerimentos');
    }
};

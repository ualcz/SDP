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
        Schema::create('documentos_assunto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assunto_requerimento_id')
                  ->constrained('assuntos_requerimentos')
                  ->cascadeOnDelete();
            $table->string('nome');                         // Ex: "Atestado Médico"
            $table->text('descricao')->nullable();          // Instrução de ajuda ao aluno
            $table->boolean('obrigatorio')->default(true);  // true = campo required no form
            $table->string('tipos_aceitos')->default('pdf,jpg,jpeg,png'); // Extensões aceitas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_assunto');
    }
};

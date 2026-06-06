<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atendimentos_agendados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evento_id')
                  ->constrained('eventos')
                  ->onDelete('cascade');

            $table->foreignId('aluno_id')
                  ->constrained('alunos')
                  ->onDelete('cascade');

            $table->enum('status', [
                'solicitado',
                'confirmado',
                'cancelado'
            ])->default('solicitado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atendimentos_agendados');
    }
};

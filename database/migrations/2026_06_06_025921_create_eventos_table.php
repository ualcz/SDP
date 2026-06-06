<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');

            $table->enum('tipo', [
                'prova',
                'trabalho',
                'seminario',
                'outro',
                'atendimento'
            ]);

            $table->text('descricao')->nullable();

            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');

            $table->string('local')->nullable();

            $table->enum('status', [
                'ativo',
                'cancelado',
                'remarcado',
                'concluido'
            ])->default('ativo');

            $table->boolean('recorrente')->default(false);

            $table->foreignId('professor_id')
                  ->nullable()
                  ->constrained('professores')
                  ->nullOnDelete();

            $table->foreignId('turma_id')
                  ->nullable()
                  ->constrained('turmas')
                  ->nullOnDelete();

            $table->boolean('criado_por_representante')
                  ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};

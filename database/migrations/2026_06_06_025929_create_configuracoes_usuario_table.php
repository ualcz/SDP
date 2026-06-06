<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes_usuario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                  ->unique()
                  ->constrained('usuarios')
                  ->onDelete('cascade');

            $table->enum('tema', [
                'claro',
                'escuro'
            ])->default('claro');

            $table->boolean('notificacao_email')
                  ->default(true);

            $table->boolean('notificacao_sistema')
                  ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_usuario');
    }
};

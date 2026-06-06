<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('usuarios')
                  ->nullOnDelete();

            $table->string('acao');
            $table->text('descricao');

            $table->timestamp('created_at')
                  ->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};

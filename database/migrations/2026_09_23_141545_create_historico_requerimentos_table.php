<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historico_requerimentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('requerimento_id')
                  ->constrained('requerimentos')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('usuarios')
                  ->onDelete('set null');

            $table->string('status');
            $table->text('observacao')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_requerimentos');
    }
};

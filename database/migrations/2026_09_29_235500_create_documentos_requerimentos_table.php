<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('documentos_requerimentos')) {
            Schema::create('documentos_requerimentos', function (Blueprint $table) {
                $table->id();

                $table->foreignId('requerimento_id')
                      ->constrained('requerimentos')
                      ->onDelete('cascade');

                $table->foreignId('historico_requerimento_id')
                      ->nullable()
                      ->constrained('historico_requerimentos')
                      ->onDelete('cascade');

                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained('usuarios')
                      ->onDelete('set null');

                $table->string('nome_documento')->nullable();
                $table->string('nome_original');
                $table->string('caminho');
                $table->string('extensao')->nullable();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('tamanho')->default(0);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_requerimentos');
    }
};

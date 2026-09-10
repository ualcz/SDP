<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Remove a tabela anexos (estava vazia, sem colunas úteis)
        Schema::dropIfExists('anexos');

        Schema::table('requerimentos', function (Blueprint $table) {
            // 2. Adiciona FK para o assunto (objetivo) do requerimento
            //    nullable para não quebrar registros já existentes
            $table->foreignId('assunto_requerimento_id')
                  ->nullable()
                  ->after('usuario_id')
                  ->constrained('assuntos_requerimentos')
                  ->nullOnDelete();

            // 3. Renomeia 'situação' (com acento) para 'status'
            $table->renameColumn('situação', 'status');
        });
    }

    public function down(): void
    {
        // Recria a tabela anexos
        Schema::create('anexos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('requerimentos', function (Blueprint $table) {
            $table->dropForeign(['assunto_requerimento_id']);
            $table->dropColumn('assunto_requerimento_id');
            $table->renameColumn('status', 'situação');
        });
    }
};

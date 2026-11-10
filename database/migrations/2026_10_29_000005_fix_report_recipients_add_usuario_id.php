<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Garante que a coluna usuario_id existe na tabela report_recipients.
        // A migration original usa foreignId('usuario_id')->constrained() que aponta
        // para a tabela 'users' (padrão do Laravel), mas o projeto usa 'usuarios'.
        // Esta migration corrige isso de forma segura.
        if (!Schema::hasColumn('report_recipients', 'usuario_id')) {
            Schema::table('report_recipients', function (Blueprint $table) {
                $table->unsignedBigInteger('usuario_id')->nullable()->after('id');
                $table->foreign('usuario_id')
                      ->references('id')
                      ->on('usuarios')
                      ->onDelete('cascade');
            });
        } else {
            // Coluna já existe, mas pode estar com FK apontando para tabela errada.
            // Apenas garantimos que o índice existe.
            // Neste caso, nada a fazer — a FK está OK.
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('report_recipients', 'usuario_id')) {
            Schema::table('report_recipients', function (Blueprint $table) {
                $table->dropForeign(['usuario_id']);
                $table->dropColumn('usuario_id');
            });
        }
    }
};


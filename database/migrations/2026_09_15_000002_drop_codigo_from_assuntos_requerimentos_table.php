<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove a coluna codigo da tabela assuntos_requerimentos.
     */
    public function up(): void
    {
        Schema::table('assuntos_requerimentos', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }

    /**
     * Restaura a coluna caso a migration seja revertida.
     */
    public function down(): void
    {
        Schema::table('assuntos_requerimentos', function (Blueprint $table) {
            $table->string('codigo')->nullable()->after('setor_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove as colunas processo_prefixo e rodape_contato da tabela setores.
     */
    public function up(): void
    {
        Schema::table('setores', function (Blueprint $table) {
            $table->dropColumn(['processo_prefixo', 'rodape_contato']);
        });
    }

    /**
     * Restaura as colunas caso a migration seja revertida.
     */
    public function down(): void
    {
        Schema::table('setores', function (Blueprint $table) {
            $table->string('processo_prefixo')->default('23720')->after('titulo');
            $table->string('rodape_contato')->nullable()->after('processo_prefixo');
        });
    }
};

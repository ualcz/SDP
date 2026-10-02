<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->foreignId('setor_retorno_id')
                ->nullable()
                ->after('setor_id')
                ->constrained('setores')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->dropForeign(['setor_retorno_id']);
            $table->dropColumn('setor_retorno_id');
        });
    }
};
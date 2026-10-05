<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assuntos_requerimentos', function (Blueprint $table) {
            $table->boolean('link_norma_ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('assuntos_requerimentos', function (Blueprint $table) {
            $table->dropColumn('link_norma_ativo');
        });
    }
};

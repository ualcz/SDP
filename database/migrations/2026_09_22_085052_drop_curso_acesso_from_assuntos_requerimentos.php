<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assuntos_requerimentos', function (Blueprint $table) {
            $table->dropColumn('curso_acesso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assuntos_requerimentos', function (Blueprint $table) {
            $table->string('curso_acesso');
        });
    }
};

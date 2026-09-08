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
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->text('motivo')->nullable()->change();
            $table->string('situação')->nullable()->default('Em Análise')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->string('motivo')->nullable(false)->change();
            $table->string('situação')->nullable(false)->default(null)->change();
        });
    }
};

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
        if (Schema::hasTable('modelos_requerimentos') && !Schema::hasTable('setores')) {
            Schema::rename('modelos_requerimentos', 'setores');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('setores') && !Schema::hasTable('modelos_requerimentos')) {
            Schema::rename('setores', 'modelos_requerimentos');
        }
    }
};

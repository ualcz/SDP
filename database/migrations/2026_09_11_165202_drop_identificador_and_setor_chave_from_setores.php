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
        Schema::table('setores', function (Blueprint $table) {
            if (Schema::hasColumn('setores', 'identificador')) {
                $table->dropColumn('identificador');
            }
            if (Schema::hasColumn('setores', 'setor_chave')) {
                $table->dropColumn('setor_chave');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setores', function (Blueprint $table) {
            $table->string('identificador')->nullable()->unique();
            $table->string('setor_chave')->nullable();
        });
    }
};

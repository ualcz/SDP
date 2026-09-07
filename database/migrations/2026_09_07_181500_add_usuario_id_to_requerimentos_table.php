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
            if (!Schema::hasColumn('requerimentos', 'usuario_id')) {
                $table->foreignId('usuario_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('usuarios')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            if (Schema::hasColumn('requerimentos', 'usuario_id')) {
                $table->dropForeign(['usuario_id']);
                $table->dropColumn('usuario_id');
            }
        });
    }
};

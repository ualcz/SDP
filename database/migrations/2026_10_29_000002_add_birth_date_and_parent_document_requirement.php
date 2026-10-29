<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('usuarios', 'data_nascimento')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->date('data_nascimento')->nullable();
            });
        }

        if (!Schema::hasColumn('documentos_assunto', 'exige_documentos_responsaveis')) {
            Schema::table('documentos_assunto', function (Blueprint $table) {
                $table->boolean('exige_documentos_responsaveis')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('documentos_assunto', 'exige_documentos_responsaveis')) {
            Schema::table('documentos_assunto', function (Blueprint $table) {
                $table->dropColumn('exige_documentos_responsaveis');
            });
        }

        if (Schema::hasColumn('usuarios', 'data_nascimento')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('data_nascimento');
            });
        }
    }
};

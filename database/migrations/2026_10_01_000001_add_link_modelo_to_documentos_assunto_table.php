<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos_assunto', function (Blueprint $table) {
            $table->string('link_modelo', 2048)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('documentos_assunto', function (Blueprint $table) {
            $table->dropColumn('link_modelo');
        });
    }
};
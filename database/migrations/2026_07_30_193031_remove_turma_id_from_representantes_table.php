<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('representantes', function (Blueprint $table) {

            $table->dropForeign('fk_representante_turma');

            $table->dropColumn('turma_id');
        });
    }

    public function down(): void
    {
        Schema::table('representantes', function (Blueprint $table) {

            $table->unsignedBigInteger('turma_id')->nullable();
        });
    }
};
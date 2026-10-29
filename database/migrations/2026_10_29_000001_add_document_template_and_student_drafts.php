<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('documentos_assunto', 'arquivo_modelo')) {
            Schema::table('documentos_assunto', function (Blueprint $table) {
                $table->string('arquivo_modelo')->nullable();
            });
        }

        if (!Schema::hasTable('requerimento_rascunhos')) {
            Schema::create('requerimento_rascunhos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
                $table->foreignId('setor_id')->constrained('setores')->cascadeOnDelete();
                $table->json('campos')->nullable();
                $table->json('arquivos')->nullable();
                $table->unsignedTinyInteger('etapa')->default(1);
                $table->timestamp('expira_em')->index();
                $table->timestamps();
                $table->unique(['usuario_id', 'setor_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('requerimento_rascunhos');

        if (Schema::hasColumn('documentos_assunto', 'arquivo_modelo')) {
            Schema::table('documentos_assunto', function (Blueprint $table) {
                $table->dropColumn('arquivo_modelo');
            });
        }
    }
};

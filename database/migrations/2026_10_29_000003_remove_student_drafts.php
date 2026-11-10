<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (Storage::disk('local')->directoryExists('requerimentos_rascunhos')
            && !Storage::disk('local')->deleteDirectory('requerimentos_rascunhos')) {
            throw new RuntimeException('Não foi possível remover os arquivos dos rascunhos.');
        }

        Schema::dropIfExists('requerimento_rascunhos');
    }

    public function down(): void
    {
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
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\Suap\EnderecoScraper;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->cascadeOnDelete();
            $table->string('rua')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->timestamps();
        });

        // Migração de dados legados de 'usuarios.endereco' para a nova tabela 'enderecos'
        if (Schema::hasColumn('usuarios', 'endereco')) {
            $usuarios = DB::table('usuarios')
                ->whereNotNull('endereco')
                ->where('endereco', '!=', '')
                ->get();

            foreach ($usuarios as $usuario) {
                $parsed = EnderecoScraper::parse($usuario->endereco);
                if ($parsed) {
                    DB::table('enderecos')->insert([
                        'usuario_id' => $usuario->id,
                        'rua' => $parsed['rua'],
                        'numero' => $parsed['numero'],
                        'bairro' => $parsed['bairro'],
                        'cidade' => $parsed['cidade'],
                        'estado' => $parsed['estado'],
                        'cep' => $parsed['cep'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Remove a coluna endereco da tabela usuarios
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('endereco');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('usuarios', 'endereco')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('endereco')->nullable()->after('email_pessoal');
            });

            // Reconstitui o campo endereco com base na tabela enderecos
            $enderecos = DB::table('enderecos')->get();
            foreach ($enderecos as $end) {
                $partes = array_filter([
                    $end->rua,
                    $end->numero,
                    $end->bairro,
                    $end->cep,
                    $end->cidade && $end->estado ? "{$end->cidade}-{$end->estado}" : $end->cidade,
                ]);
                DB::table('usuarios')->where('id', $end->usuario_id)->update([
                    'endereco' => implode(', ', $partes),
                ]);
            }
        }

        Schema::dropIfExists('enderecos');
    }
};

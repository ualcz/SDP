<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $enderecos = DB::table('enderecos')->get();
        foreach ($enderecos as $endereco) {
            if (!empty($endereco->numero)) {
                $novaRua = trim($endereco->rua . ', ' . $endereco->numero);
                DB::table('enderecos')->where('id', $endereco->id)->update([
                    'rua' => $novaRua,
                    'numero' => null,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

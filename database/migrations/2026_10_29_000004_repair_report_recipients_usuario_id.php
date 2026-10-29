<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('report_recipients')) {
            throw new RuntimeException('A tabela report_recipients não existe.');
        }

        if (Schema::hasColumn('report_recipients', 'usuario_id')) {
            return;
        }

        Schema::table('report_recipients', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id')->nullable();
        });

        if (Schema::hasColumn('report_recipients', 'user_id')) {
            DB::table('report_recipients')
                ->whereNull('usuario_id')
                ->update(['usuario_id' => DB::raw('user_id')]);
        }
    }

    public function down(): void
    {
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->string('email_message_id', 320)->nullable()->after('numero_protocolo');
        });
    }

    public function down(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->dropColumn('email_message_id');
        });
    }
};
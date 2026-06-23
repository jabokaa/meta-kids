<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crianca_metas', function (Blueprint $table) {
            $table->boolean('bloquear_dias_anteriores')->default(false)->after('maximo_por_dia');
        });
    }

    public function down(): void
    {
        Schema::table('crianca_metas', function (Blueprint $table) {
            $table->dropColumn('bloquear_dias_anteriores');
        });
    }
};

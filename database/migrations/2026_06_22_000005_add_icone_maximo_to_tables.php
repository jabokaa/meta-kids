<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crianca_metas', function (Blueprint $table) {
            $table->unsignedInteger('maximo_por_dia')->default(3)->after('valor_meta');
        });

        Schema::table('registro_metas', function (Blueprint $table) {
            $table->string('icone', 10)->default('⭐')->after('data');
        });
    }

    public function down(): void
    {
        Schema::table('crianca_metas', function (Blueprint $table) {
            $table->dropColumn('maximo_por_dia');
        });
        Schema::table('registro_metas', function (Blueprint $table) {
            $table->dropColumn('icone');
        });
    }
};

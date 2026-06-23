<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criancas', function (Blueprint $table) {
            $table->string('imagem')->nullable()->after('data_nascimento');
            $table->integer('estilo')->default(0)->after('imagem');
        });
    }

    public function down(): void
    {
        Schema::table('criancas', function (Blueprint $table) {
            $table->dropColumn(['imagem', 'estilo']);
        });
    }
};

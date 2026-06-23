<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crianca_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crianca_id')->constrained('criancas')->cascadeOnDelete();
            $table->string('descricao');
            $table->string('metas');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->enum('tipo', ['semanal', 'mensal']);
            $table->integer('valor_meta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crianca_metas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metas_em_andamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_id')->constrained('crianca_metas')->cascadeOnDelete();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->unsignedInteger('contador')->default(0);
            $table->boolean('concluida')->default(false);
            $table->timestamps();
            $table->unique(['meta_id', 'data_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metas_em_andamento');
    }
};

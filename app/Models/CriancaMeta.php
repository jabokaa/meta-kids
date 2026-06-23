<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['crianca_id', 'descricao', 'metas', 'data_inicio', 'data_fim', 'tipo', 'valor_meta', 'maximo_por_dia', 'bloquear_dias_anteriores'])]
class CriancaMeta extends Model
{
    protected function casts(): array
    {
        return [
            'data_inicio'    => 'date',
            'data_fim'       => 'date',
            'valor_meta'     => 'integer',
            'maximo_por_dia'          => 'integer',
            'bloquear_dias_anteriores' => 'boolean',
        ];
    }

    public function crianca(): BelongsTo
    {
        return $this->belongsTo(Crianca::class);
    }

    public function registros(): HasMany
    {
        return $this->hasMany(RegistroMeta::class, 'meta_id');
    }

    public function periodos(): HasMany
    {
        return $this->hasMany(MetaEmAndamento::class, 'meta_id')->orderBy('data_inicio');
    }
}

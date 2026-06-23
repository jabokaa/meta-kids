<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['meta_id', 'data_inicio', 'data_fim', 'contador', 'concluida'])]
class MetaEmAndamento extends Model
{
    protected $table = 'metas_em_andamento';

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date:Y-m-d',
            'data_fim'    => 'date:Y-m-d',
            'contador'    => 'integer',
            'concluida'   => 'boolean',
        ];
    }

    public function meta(): BelongsTo
    {
        return $this->belongsTo(CriancaMeta::class, 'meta_id');
    }
}

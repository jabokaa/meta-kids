<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['meta_id', 'data', 'icone'])]
class RegistroMeta extends Model
{
    protected function casts(): array
    {
        return [
            'data' => 'date:Y-m-d',
        ];
    }

    public function meta(): BelongsTo
    {
        return $this->belongsTo(CriancaMeta::class, 'meta_id');
    }
}

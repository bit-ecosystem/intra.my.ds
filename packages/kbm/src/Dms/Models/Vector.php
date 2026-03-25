<?php

declare(strict_types=1);

namespace Bites\Kbm\Dms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vector extends Model
{
    protected $table = 'dms_vectors';

    protected $fillable = [
        'document_id',
        'model',
        'chunk_text',
        'vector',
        'metadata',

    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    protected function casts(): array
    {
        return [
            'vector' => 'array',
            'metadata' => 'array',
        ];
    }
}

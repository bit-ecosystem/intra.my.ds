<?php

declare(strict_types=1);

namespace Bites\Service\Models;

use Bites\Service\Observers\ApiDataObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ApiDataObserver::class])]
class ApiData extends Model
{
    protected $table = 'api_data';

    protected $fillable = [
        'content',
        'source',
    ];

    // Make model events (created) fire after DB commit to avoid race conditions
    protected $afterCommit = true;

    protected function casts(): array
    {
        return [
            'content' => 'array',   // ensures JSON<->array
            // 'timestamp' => 'datetime',
            // 'received_at' => 'datetime',
        ];
    }
}

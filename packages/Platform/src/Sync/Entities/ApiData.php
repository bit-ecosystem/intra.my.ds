<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Entities;

use Bites\Platform\Sync\Observers\ApiDataObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ApiDataObserver::class])]
#[Fillable([
    'api_config_id',
    'payload',
    'fingerprint',
    'status',
    'processed_at',
    'error',
])]
class ApiData extends Model
{
    public function api_config()
    {
        return $this->belongsTo(ApiConfig::class);
    }

    public function markProcessed(): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
            'error' => null,
            'payload' => 'committed',
        ]);
    }

    public function markFailed(string $message): void
    {
        $this->update([
            'status' => 'failed',
            'error' => $message,
        ]);
    }

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'processed_at' => 'datetime',
        ];
    }
}

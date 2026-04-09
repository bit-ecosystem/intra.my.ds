<?php

declare(strict_types=1);

namespace Bites\Service\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AttachableExtLink extends Model
{
    protected $table = 'attachable_ext_links';

    protected $fillable = ['url'];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        // Normalize input and enforce "empty => delete/skip" rule
        static::saving(function (self $model): bool {
            // Normalize / trim
            $model->url = trim((string) ($model->url ?? ''));

            // If empty URL:
            if ($model->url === '') {
                if ($model->exists) {
                    // If updating an existing row to empty, delete instead of saving empty.
                    // Return false to cancel the save.
                    $model->delete();

                    return false;
                }

                // If attempting to create with empty URL, cancel creation.
                return false;
            }

            // Otherwise allow save to proceed.
            return true;
        });

        // Optional: if you prefer to also guard at "creating" boundary specifically.
        static::creating(function (self $model): bool {
            $model->url = trim((string) ($model->url ?? ''));

            return $model->url !== '';
        });

        // Optional: if you want to enforce HTTPS only (uncomment if desired):
        // static::saving(function (self $model) {
        //     if (! str_starts_with($model->url, 'https://')) {
        //         throw new \InvalidArgumentException('URL must start with https://');
        //     }
        // });
    }

    protected function url(): Attribute
    {
        return Attribute::make(set: function ($value): array {
            return ['url' => trim((string) ($value ?? ''))];
        });
    }
}

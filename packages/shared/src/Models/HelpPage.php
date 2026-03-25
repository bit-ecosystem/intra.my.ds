<?php

declare(strict_types=1);

namespace Bites\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class HelpPage extends Model
{
    protected $fillable = [
        'page_class',
        'title',
        'content',
        'is_active',
        'record',
    ];

    /**
     * Resolve help for a Filament page class.
     */
    public static function resolveForPage(string $pageClass): ?self
    {
        return static::query()
            ->where('page_class', $pageClass)
            ->where('is_active', true)
            ->first();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}

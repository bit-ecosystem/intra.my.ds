<?php

declare(strict_types=1);

namespace App\Models\Core;

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

    protected $casts = [
        'is_active' => 'boolean',
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
}

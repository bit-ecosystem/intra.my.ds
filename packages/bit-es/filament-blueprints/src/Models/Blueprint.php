<?php

declare(strict_types=1);

namespace Bites\FilamentBlueprints\Models;

use Illuminate\Database\Eloquent\Model;

class Blueprint extends Model
{
    protected $fillable = ['name', 'form_blocks', 'infolist_blocks', 'action_blocks'];

    protected function casts(): array
    {
        return [
            'form_blocks' => 'array',
            'infolist_blocks' => 'array',
            'action_blocks' => 'array',
        ];
    }
}

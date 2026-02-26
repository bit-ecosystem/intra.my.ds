<?php

declare(strict_types=1);

namespace App\Models\Qas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Methodology extends Model
{
    protected $table = 'q_methodologies';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function runs(): HasMany
    {
        return $this->hasMany(RunInitiative::class, 'methodology_id');
    }

    protected function casts(): array
    {
        return [
            'form_schema' => 'array',
            'report_schema' => 'array',
            'needs_form' => 'boolean',
            'needs_report' => 'boolean',
        ];
    }

    // App\Models\Qas\Methodology.php
    public static function idByName(string $methodology): ?int
    {
        return static::where('methodology', $methodology)->value('id');
    }
}

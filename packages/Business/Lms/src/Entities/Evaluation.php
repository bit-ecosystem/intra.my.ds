<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Entities;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'l_evaluations';

    protected $fillable = [
        'module_id',
        'code',
        'name',          // optional, as per your migration
        'is_active',
        'schema',        // JSON (your quiz schema)
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'evaluation_id');
    }

    /** Scope: active quizzes only */
    #[Scope]
    protected function active(Builder $builder): Builder
    {
        return $builder->where('is_active', true);
    }

    protected function casts(): array
    {
        return [
            'schema' => 'array',
            'is_active' => 'boolean',
        ];
    }
}

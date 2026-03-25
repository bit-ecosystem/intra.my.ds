<?php

declare(strict_types=1);

namespace App\Models\Lms;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'l_quizzes';

    protected $fillable = [
        'module_id',
        'code',
        'name',          // optional, as per your migration
        'passing_mark',  // DECIMAL(8,2) per your schema (choose ratio vs percent consistently in your app)
        'is_active',
        'examiner_style',
        'schema',        // JSON (your quiz schema)
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
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
            'examiner_style' => 'boolean',
            'passing_mark' => 'decimal:2',
        ];
    }
}

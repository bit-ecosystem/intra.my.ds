<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Entities;

use Bites\Service\Concerns\HasAttachableRoles;
use Bites\Service\Concerns\HasStakeHolder;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

#[UsePolicy(ModulePolicy::class)]
#[UseFactory(ModuleFactory::class)]
class Module extends Model
{
    use HasAttachableRoles;
    use HasFactory,HasStakeHolder;

    protected $table = 'l_modules';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'order_index', // only relevant if using 1:N course->modules
        'estimated_duration_minutes',
        'validity_months',
        'certificate_template',
        // 'course_id', // only relevant if using 1:N course->modules
    ];

    /**
     * 1:N path (because l_modules has course_id).
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * M:N path via pivot l_course_module.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'l_course_module', 'module_id', 'course_id')
            ->withPivot('order_index');
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'l_material_module')
            ->withPivot(['order_index'])
            ->withTimestamps()
            ->orderBy('l_material_module.order_index');
    }

    public function quizzes(): HasOne
    {
        return $this->hasOne(Quiz::class, 'module_id')->orderBy('is_active', 'desc');
    }

    public function evaluations(): HasOne
    {
        return $this->hasOne(Evaluation::class, 'evaluation_id');
    }

    public static function resolveCreation(array $record, $index)
    {
        // 1) Create or update the base menu

        $module = self::updateOrCreate(
            // If you *really* want to match by id, only pass it when it exists
            isset($record['id']) ? ['id' => $record['id']] : ['slug' => Str::slug(($record['name'] ?? $record['title'] ?? 'untitled').'-'.fake()->unique()->lexify('????'))],
            [
                'title' => $record['name'] ?? $record['title'] ?? 'Untitled Module',
                'description' => $record['description'] ?? '',
                'order_index' => $index + 1,
                'estimated_duration_minutes' => $record['estimated_duration_minutes'] ?? 60,
                'validity_months' => 12,
                'certificate_template' => [],

                // IMPORTANT: If the match doesn’t include slug, include slug here too
                // to ensure INSERT will have a slug
                'slug' => Str::slug(($record['name'] ?? $record['title'] ?? 'untitled').'-'.fake()->unique()->lexify('????')),
            ]
        );

        // 2) Attach roles if provided
        if (! empty($record['roles'])) {
            $module->attachRolesFromMixed($record['roles'], [
                'sync_per_team' => false, // set true if you want replace-per-team behavior
            ]);
        } else {
            $module->attachRolesFromMixed(['ut_staff'], [
                'sync_per_team' => false, // set true if you want replace-per-team behavior
            ]);
        }

        return $module->refresh();
    }

    protected function casts(): array
    {
        return [
            'certificate_template' => 'array',
            'validity_months' => 'integer',
            'estimated_duration_minutes' => 'integer',
        ];
    }
}

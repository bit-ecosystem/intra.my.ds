<?php

declare(strict_types=1);

namespace Bites\Base\Todo\Concerns;

use Bites\Organization\Authorization\RoleMapper;
use Bites\Employment\Models\Staff;
use Bites\Base\Todo\Task;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

trait CanMakeTask
{
    /**
     * Boot the trait to cascade delete related tasks when
     * the parent is deleted (polymorphic relations don’t cascade in DB).
     */
    public static function bootCanMakeTask(): void
    {
        static::deleting(function ($model): void {
            // If using SoftDeletes and you DON’T want to delete tasks on soft delete,
            // guard this with $model->isForceDeleting().
            if (method_exists($model, 'isForceDeleting')) {
                if ($model->isForceDeleting()) {
                    $model->tasks()->delete();
                }
            } else {
                $model->tasks()->delete();
            }
        });
    }

    /**
     * Polymorphic relation: this model "has many" tasks.
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    /**
     * Create a task attached to this model.
     */
    public function makeTask(array $attributes): Task
    {
        // Allow only safe fillable fields and apply defaults
        $data = Arr::only($attributes, [
            'title',
            'description',
            'due_at',
            'status',
            'staff_id',
            'role_mapper_id',
        ]);

        $data['status'] = $data['status'] ?? 'pending';

        return $this->tasks()->create($data);
    }

    /**
     * Convenience: assign task to a staff and/or role in one shot.
     */
    public function makeTaskForStaff(array $attributes, $staff): Task
    {
        $attributes['staff_id'] = $staff instanceof Staff ? $staff->getKey() : $staff;

        return $this->makeTask($attributes);
    }

    public function makeTaskForRole(array $attributes, $roleMapper): Task
    {
        $attributes['role_mapper_id'] = $roleMapper instanceof RoleMapper ? $roleMapper->getKey() : $roleMapper;

        return $this->makeTask($attributes);
    }

    /**
     * Quick helpers to update a task’s assignment/status safely.
     */
    public function assignTaskToStaff(Task $task, $staff): Task
    {
        $task->update([
            'staff_id' => $staff instanceof Staff ? $staff->getKey() : $staff,
        ]);

        return $task->fresh();
    }

    public function assignTaskToRole(Task $task, $roleMapper): Task
    {
        $task->update([
            'role_mapper_id' => $roleMapper instanceof RoleMapper ? $roleMapper->getKey() : $roleMapper,
        ]);

        return $task->fresh();
    }

    public function markTaskStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);

        return $task->fresh();
    }

    public function completeTask(Task $task): Task
    {
        return $this->markTaskStatus($task, 'done');
    }

    // public function getModelStatusAttribute(): mixed
    // {
    //     $this->loadMissing('taskable');

    //     $owner = $this->taskable;
    //     if (! $owner) {
    //         return null;
    //     }

    //     // If the taskable uses the CanMakeTask trait, it already has getModelStatus()
    //     if (method_exists($owner, 'getModelStatus')) {
    //         return $owner->getModelStatus();
    //     }

    //     // Otherwise, try the common patterns:
    //     if (method_exists($owner, 'status')) {
    //         return $owner->status();
    //     }

    //     if (method_exists($owner, '__isset') && $owner->__isset('status')) {
    //         return $owner->status;
    //     }

    //     if (method_exists($owner, 'getAttributes')) {
    //         $attributes = $owner->getAttributes();
    //         if (array_key_exists('status', $attributes)) {
    //             return $owner->status;
    //         }
    //     }

    //     return null;
    // }

    // public function getModelStatus(): mixed
    // {
    //     // 1) Prefer a dedicated method if the model defines one
    //     if (method_exists($this, 'status')) {
    //         $value = $this->status();
    //         return $this->normalizeStatusValue($value);
    //     }

    //     // 2) Attribute/Property (handles column and getStatusAttribute())
    //     // __isset() will return true if the attribute or accessor exists and is non-null-ish
    //     if (method_exists($this, '__isset') && $this->__isset('status')) {
    //         return $this->normalizeStatusValue($this->status);
    //     }

    //     // If attribute is present in raw attributes, respect it (even if null)
    //     if (method_exists($this, 'getAttributes')) {
    //         $attributes = $this->getAttributes();
    //         if (array_key_exists('status', $attributes)) {
    //             return $this->normalizeStatusValue($this->status);
    //         }
    //     }

    //     // 3) Optional: raw original fallback (bypass accessors/mutators)
    //     if (method_exists($this, 'getRawOriginal')) {
    //         $raw = $this->getRawOriginal('status', null);
    //         if ($raw !== null) {
    //             return $this->normalizeStatusValue($raw);
    //         }
    //     }

    //     // Nothing found
    //     return null;
    // }

    /**
     * Normalize enums / value objects / scalars to something usable in UI/filters.
     */
    protected function normalizeStatusValue(mixed $value): mixed
    {
        if (is_object($value)) {
            // PHP 8.1+ backed enums
            if (function_exists('enum_exists') && enum_exists($value::class) && property_exists($value, 'value')) {
                return $value->value;
            }

            // Common "value()" method pattern
            if (method_exists($value, 'value')) {
                return $value->value();
            }

            // Stringable
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
        }

        return $value;
    }
}

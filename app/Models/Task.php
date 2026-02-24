<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_at',
        'status',
        'staff_id',
        'role_mapper_id',
    ];

    public function taskable()
    {
        return $this->morphTo();
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\Hrm\Staff::class);
    }

    public function roleMapper()
    {
        return $this->belongsTo(\App\Models\RoleMapper::class);
    }

    /* ---- Useful scopes ---- */

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function pending($query)
    {
        return $query->where('status', 'pending');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function due($query)
    {
        return $query->whereNotNull('due_at')->orderBy('due_at');
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function overdue($query)
    {
        return $query->where('status', '!=', 'done')
            ->whereNotNull('due_at')
            ->where('due_at', '<', now());
    }

    protected function modelStatus(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            $this->loadMissing('taskable');
            $owner = $this->taskable;
            if (! $owner) {
                return null;
            }

            // If the owner model uses the trait with getModelStatus(), prefer that
            if (method_exists($owner, 'getModelStatus')) {
                return $owner->getModelStatus();
            }

            // Fallbacks if trait is not present on the owner
            if (method_exists($owner, 'status')) {
                return $owner->status();
            }

            if (method_exists($owner, '__isset') && $owner->__isset('status')) {
                return $owner->status;
            }

            if (method_exists($owner, 'getAttributes')) {
                $attributes = $owner->getAttributes();
                if (array_key_exists('status', $attributes)) {
                    return $owner->status;
                }
            }

            return null;
        });
    }

    // (Optional) If you also want method call: $t->getModelStatus()
    public function getModelStatus(): mixed
    {
        return $this->model_status;
    }

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
        ];
    }
}

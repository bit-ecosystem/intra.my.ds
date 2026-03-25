<?php

declare(strict_types=1);

namespace Bites\Hrm\Models;

use Bites\Core\Organization\Models\JobPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobDescriptionTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'attributes',
        'masco_code',
    ];

    public function workforcePlans(): HasMany
    {
        return $this->hasMany(WorkforcePlan::class, 'job_title_id');
    }

    public function jobPositions(): HasMany
    {
        return $this->hasMany(JobPosition::class, 'job_title_id');
    }

    protected function casts(): array
    {
        return [
            'attributes' => 'array', // Automatically cast JSON to array
        ];
    }
}

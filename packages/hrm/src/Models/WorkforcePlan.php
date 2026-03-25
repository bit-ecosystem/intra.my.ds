<?php

declare(strict_types=1);

namespace Bites\Hrm\Models;

use Bites\Core\Organization\Models\OrgUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Bites\Core\Organization\Models\JobPosition;

class WorkforcePlan extends Model
{
    protected $fillable = [
        'org_unit_id',
        'title',
        'job_title_id',
        'required_quantity',
    ];

    // Relationships
    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function JobDescriptionTemplate(): BelongsTo
    {
        return $this->belongsTo(JobDescriptionTemplate::class, 'job_title_id');
    }

    public function jobPositions(): HasMany
    {
        return $this->hasMany(JobPosition::class, 'org_unit_id', 'org_unit_id')
            ->whereColumn('title', 'workforce_plans.title');
    }

    public function jobPositionsFilled(): HasMany
    {
        return $this->hasMany(JobPosition::class, 'org_unit_id', 'org_unit_id')
            ->whereColumn('title', 'workforce_plans.title')
            ->whereHas('staff'); // Only positions that have staff assigned
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Hrm;

use App\Models\Core\OrgRole;
use App\Models\Core\OrgUnit;
use App\Observers\JobPositionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([JobPositionObserver::class])]
class JobPosition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id');
    }

    public function orgRoles()
    {
        return $this->hasMany(OrgRole::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'job_position_id');
    }

    public function hasVacancy(): bool
    {
        return $this->jobVacancy()->exists();
    }

    public function jobVacancy()
    {
        return $this->hasOne(JobVacancy::class, 'job_position_id')
            ->where('status', '<>', 'closed'); // Exclude closed vacancies
    }

    public function user()
    {
        return $this->hasOneThrough(
            \App\Models\User::class,      // Final model
            \App\Models\Hrm\Staff::class, // Intermediate model
            'job_position_id',            // Foreign key on Staff table
            'id',                         // Foreign key on User table
            'id',                         // Local key on JobPosition table
            'user_id'                     // Local key on Staff table
        );
    }

    public function superior()
    {
        return $this->belongsTo(JobPosition::class, 'superior_id');
    }

    public function subordinates()
    {
        return $this->hasMany(JobPosition::class, 'superior_id');
    }

    protected function isPeopleManager(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            // returns true if there exists at least one subordinate
            return $this->subordinates()->exists();
        });
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skill');
    }
}

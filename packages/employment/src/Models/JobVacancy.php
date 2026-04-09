<?php

declare(strict_types=1);

namespace Bites\Employment\Models;

use App\Models\User;
use Bites\Organization\Structure\JobPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    protected $table = 'r_job_vacancies';

    use HasFactory;

    protected $fillable = [
        'job_position_id',
        'location',
        'responsibilities',
        'qualifications',
        'salary_range',
        'status',
        'posted_by',
    ];

    // Relationships
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    protected function casts(): array
    {
        return [
            'responsibilities' => 'array',
            'qualifications' => 'array',
        ];
    }
}

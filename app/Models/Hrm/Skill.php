<?php

declare(strict_types=1);

namespace App\Models\Hrm;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'r_skills';

    use HasFactory;

    protected $fillable = ['name'];

    public function jobs()
    {
        return $this->belongsToMany(JobPosition::class, 'job_skill');
    }

    public function candidates()
    {
        return $this->belongsToMany(User::class, 'candidate_skill');
    }
}

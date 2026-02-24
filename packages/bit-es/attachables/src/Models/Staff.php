<?php

declare(strict_types=1);

namespace Bites\Attachables\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $guarded = [];

    /**
     * For Staff and User models:
     * A model can have many job positions assigned.
     */
    public function jobPositions()
    {
        return $this->morphMany(JobPosition::class, 'assignable');
    }
}

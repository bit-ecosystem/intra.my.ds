<?php

declare(strict_types=1);

namespace Bites\Attachables\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $guarded = [];

    /**
     * For JobPosition model:
     * A job position belongs to either Staff or User.
     */
    public function assignable()
    {
        return $this->morphTo();
    }
}

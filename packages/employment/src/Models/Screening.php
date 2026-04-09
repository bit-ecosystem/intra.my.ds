<?php

declare(strict_types=1);

namespace Bites\Employment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $table = 'r_screenings';

    use HasFactory;

    protected $fillable = ['application_id', 'score', 'remarks', 'status'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}

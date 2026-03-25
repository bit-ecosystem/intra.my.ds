<?php

declare(strict_types=1);

namespace Bites\Hrm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'r_offers';

    use HasFactory;

    protected $fillable = ['application_id', 'offer_letter_path', 'salary_offered', 'status', 'sent_at'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}

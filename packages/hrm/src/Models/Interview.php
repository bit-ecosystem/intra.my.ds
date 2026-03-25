<?php

declare(strict_types=1);

namespace Bites\Hrm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $table = 'r_interviews';

    use HasFactory;

    protected $fillable = ['application_id', 'interviewer_id', 'scheduled_at', 'mode', 'status', 'feedback'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}

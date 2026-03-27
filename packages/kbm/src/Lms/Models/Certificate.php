<?php

declare(strict_types=1);

namespace Bites\Kbm\Lms\Models;

use Bites\Hrm\Models\Staff;
use Bites\Shared\Concerns\CanMakeTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use CanMakeTask;
    use HasFactory;

    protected $table = 'l_certificates';

    protected $fillable = [
        'module_id',
        'for_staff',
        'by_staff',
        'quiz_attempt_id',
        'quiz_id',
        'certificate_number',
        'title',
        'issued_at',
        'expires_at',
        'payload',
        'action', // pending|completed|none
        'status', // valid|expired|revoked
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'for_staff');
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'by_staff');
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'payload' => 'array',
        ];
    }
}

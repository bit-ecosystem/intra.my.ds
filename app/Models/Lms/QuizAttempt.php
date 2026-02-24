<?php

declare(strict_types=1);

namespace App\Models\Lms;

use App\Models\Hrm\Staff;
use App\Observers\QuizAttemptObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([QuizAttemptObserver::class])]
class QuizAttempt extends Model
{
    use HasFactory;

    protected $table = 'l_quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'module_id',
        'user_id',
        'for_staff',
        'by_staff',
        'data',          // JSON answers / filled form
        'result',        // pass|fail|incomplete|pending
        'score',         // DECIMAL(8,2) per your schema (define % vs ratio in your app logic)
        'started_at',
        'time_taken',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Hrm\Staff::class, 'for_staff');
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Hrm\Staff::class, 'by_staff');
    }

    public static function resolveCreation(array $data): self
    {

        if (isset($data['meta'])) {
            if ($data['meta'] !== '') {
                $parts = array_map('trim', explode(',', $data['meta'] ?? ''));
                $taker = Staff::firstWhere('staff_number', $parts[0])?->id;
                $examiner = Staff::firstWhere('staff_number', $parts[1])?->id;
                $data['for_staff'] = $taker;
                $data['by_staff'] = $examiner;
            }
        }
        unset($data['user_id'],$data['meta']);

        return self::create($data);
    }

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'score' => 'decimal:2',
            'started_at' => 'datetime',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Bites\Knowledge\Learning;

use Carbon\Carbon;
use Illuminate\Support\Str;

class QuizAttemptObserver
{
    /**
     * Ensure we only run after the DB transaction commits.
     * Requires Laravel 9.16+ / 10.x.
     */
    public bool $afterCommit = true;

    /**
     * Handle the QuizAttempt "created" event.
     */
    public function created(QuizAttempt $quizAttempt): void
    {
        // Only issue certificate if the attempt is a pass
        if (! $quizAttempt->result) {
            return;
        }

        // Optional: prevent duplicates if the job retried / observer called twice.
        // If you already have a unique index on quiz_attempt_id, this is extra protection.
        $alreadyIssued = Certificate::query()
            ->where('quiz_attempt_id', $quizAttempt->id)
            ->exists();

        if ($alreadyIssued) {
            return;
        }

        // Use started_at as issuance time; fallback to now()
        $issuedAt = $quizAttempt->started_at ? Carbon::parse($quizAttempt->started_at) : now();

        // Determine module (use attempt.module_id; fallback to quiz.module_id)
        $moduleId = $quizAttempt->module_id ?? optional($quizAttempt->quiz)->module_id;
        $module = $moduleId ? Module::find($moduleId) : null;

        // Expiry calculation
        $expiresAt = null;
        $validityMonths = $module?->validity_months;
        if (! is_null($validityMonths) && $validityMonths > 0) {
            $expiresAt = (clone $issuedAt)->addMonths($validityMonths);
        }

        // Build certificate number
        $certificateNumber = $this->makeCertificateNumber(
            moduleSlug: $module?->slug,
            staffNumber: isset($quizAttempt->for_staff) ? (string) $quizAttempt->for_staff : null,
            issuedAt: $issuedAt,
        );

        // Prepare payload
        $payload = [
            'attempt_id' => $quizAttempt->id,
            'user_id' => $quizAttempt->user_id,
            'user_name' => optional($quizAttempt->user)->name,
            'module_id' => $module?->id,
            'module' => $module?->title,
            'quiz_id' => $quizAttempt->quiz_id,
            'quiz_name' => optional($quizAttempt->quiz)->name,
            'score' => $quizAttempt->score,
            'issued_at' => $issuedAt->toIso8601String(),
            'expires_at' => $expiresAt?->toIso8601String(),
            'qr_data' => $certificateNumber,
        ];
        Certificate::updateOrCreate(
            [
                'for_staff' => $quizAttempt->for_staff,
                'quiz_id' => $quizAttempt->quiz_id,
            ],
            [
                'module_id' => $module?->id,
                'by_staff' => $quizAttempt->by_staff,
                'quiz_attempt_id' => $quizAttempt->id,
                'certificate_number' => $certificateNumber,
                'issued_at' => $issuedAt,
                'expires_at' => $expiresAt,
                'payload' => $payload,
                'status' => 'valid',
                'action' => 'completed',
            ]
        );
    }

    protected function makeCertificateNumber(?string $moduleSlug, ?string $staffNumber, Carbon $issuedAt): string
    {
        $slug = $moduleSlug ? Str::upper(Str::slug($moduleSlug)) : 'MODULE';

        return sprintf(
            'CERT-%s-%s-%s',
            $slug,
            $staffNumber,
            $issuedAt->format('YmdHis')
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\QuizAttempts\Pages;

use App\Filament\Lms\Resources\QuizAttempts\QuizAttemptResource;
use App\Services\QuizScoringService;
use Bites\Kbm\Lms\Models\Certificate;
use Bites\Kbm\Lms\Models\Module;
use Bites\Kbm\Lms\Models\Quiz;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateQuizAttempt extends CreateRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected static bool $canCreateAnother = false;

    protected static bool $shouldRegisterNavigation = false;

    protected function getCreateFormAction(): Action
    {
        $hasFormWrapper = $this->hasFormWrapper();

        return Action::make('create')
            ->label('Submit Quiz')
            ->submit($hasFormWrapper ? $this->getSubmitFormLivewireMethodName() : null)
            ->action($hasFormWrapper ? null : $this->getSubmitFormLivewireMethodName())
            ->keyBindings(['mod+s']);
    }

    public function mount(): void
    {
        parent::mount();

        $formId = request()->input('form_id');
        if ($formId) {
            $form = Quiz::find($formId);
            $schema = $this->shuffleQuizOptions($form->schema);
            if ($form) {
                $this->form->fill([
                    'form_id' => $formId,
                    'examiner_style' => $form->examiner_style,
                    'form_schema' => $schema, // ✅ Pass actual schema array
                    'user_id' => Auth::user()->id,
                    'by_staff' => Auth::user()->staff->id,
                    'for_staff' => Auth::user()->staff->id,
                    'started_at' => now()->toDateTimeString(),
                ]);
            }
        }
    }

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        $formId = request()->get('form_id');
        $formName = $formId ? Quiz::find($formId)?->name : 'New Entry';

        return __('Attempting ').$formName;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Get the form schema
        $form = Quiz::find($data['form_id']);
        if (! $form) {
            return $data;
        }

        $data['quiz_id'] = $form->id;
        $data['module_id'] = $form->module_id;

        // Decode schema and user entry
        $schema = $form->schema;
        $entry = $data['data'];

        // Use the service
        $quizScoringService = app(QuizScoringService::class);
        $result = $quizScoringService->scoreQuiz($schema, $entry);

        // ✅ Calculate numeric score (ratio)
        $score = $result['max'] > 0 ? ($result['total'] / $result['max']) * 100 : 0;

        // Store as decimal (percentage)
        $data['score'] = round($score, 2); // e.g., 60.00 for 3/5
        $start = empty($data['started_at']) ? null : Carbon::parse($data['started_at']);
        $data['time_taken'] = $start instanceof Carbon ? round($start->diffInMilliseconds(Carbon::now()) / 100) / 10 : null;
        $data['result'] = $data['score'] >= (float) $form->passing_mark;
        if (empty($data['for_staff'])) {
            $data['for_staff'] = $data['by_staff'];
        }

        // dd($data);
        return $data;
    }

    /**
     * After the attempt is created, issue a certificate if the result is true.
     */
    // protected function afterCreate(): void
    // {
    //     /** @var \Bites\Kbm\Lms\Models\QuizAttempt $attempt */
    //     $attempt = $this->record;

    //     // Only issue certificate on pass
    //     if (! $attempt || ! $attempt->result) {
    //         return;
    //     }

    //     // Use started_at as issuance time; fallback to now()
    //     $issuedAt = $attempt->started_at ? Carbon::parse($attempt->started_at) : now();

    //     // Get module (prefer attempt->module_id; fallback to quiz relation)
    //     $moduleId = $attempt->module_id ?? optional($attempt->quiz)->module_id;
    //     $module = $moduleId ? Module::find($moduleId) : null;

    //     // Compute expiry based on module validity_months
    //     $expiresAt = null;
    //     $validityMonths = $module?->validity_months;
    //     if (! is_null($validityMonths) && $validityMonths > 0) {
    //         $expiresAt = (clone $issuedAt)->addMonths($validityMonths);
    //     }

    //     // Build a unique certificate number (adjust to your taste)
    //     $certificateNumber = $this->makeCertificateNumber(
    //         moduleSlug: $module?->slug,
    //         userId: $attempt->user_id,
    //         issuedAt: $issuedAt,
    //     );

    //     // Optional payload for your PDF/QR renderer
    //     $payload = [
    //         'attempt_id' => $attempt->id,
    //         'user_id' => $attempt->user_id,
    //         'user_name' => optional($attempt->user)->name,
    //         'module_id' => $module?->id,
    //         'module' => $module?->title,
    //         'quiz_id' => $attempt->quiz_id,
    //         'quiz_name' => optional($attempt->quiz)->name,
    //         'score' => $attempt->score,
    //         'issued_at' => $issuedAt->toIso8601String(),
    //         'expires_at' => $expiresAt?->toIso8601String(),
    //         'qr_data' => $certificateNumber, // or your preferred QR data format
    //     ];

    //     // Create certificate
    //     Certificate::create([
    //         'module_id' => $module?->id,
    //         'for_staff' => $attempt->for_staff,
    //         'by_staff' => $attempt->by_staff,
    //         'quiz_attempt_id' => $attempt->id,
    //         'certificate_number' => $certificateNumber,
    //         'title' => $module?->title ?? 'Certificate',
    //         'issued_at' => $issuedAt,
    //         'expires_at' => $expiresAt,
    //         'payload' => $payload,
    //         'status' => 'valid',      // enum: valid|expired|revoked
    //         'action' => 'completed',  // enum: pending|completed|none
    //     ]);
    // }

    /**
     * Generate a unique, readable certificate number.
     */
    // protected function makeCertificateNumber(?string $moduleSlug, ?int $userId, Carbon $issuedAt): string
    // {
    //     $slug = $moduleSlug ? Str::upper(Str::slug($moduleSlug)) : 'MODULE';

    //     return sprintf('CERT-%s-%d-%s', $slug, $userId ?? 0, $issuedAt->format('YmdHis'));
    // }

    /**
     * Shuffle quiz options in the given schema using a seed.
     *
     * - If no $seed is provided, it uses current timestamp (seconds).
     * - Only shuffles items where type === 'quiz' and data.options is an array.
     * - Preserves each option's 'data.key' and 'data.correct'.
     *
     * @param  array  $schema  The decoded schema array (e.g., from $form->schema)
     * @param  int|null  $seed  Optional, deterministic seed for shuffling
     * @return array Shuffled schema
     */
    protected function shuffleQuizOptions(array $schema): array
    {
        $seed ?? now()->timestamp; // or (int) (microtime(true) * 1000) for higher variance

        return array_map(function ($item) {
            if (
                is_array($item)
                && ($item['type'] ?? null) === 'quiz'
                && isset($item['data']['options'])
                && is_array($item['data']['options'])
            ) {
                $item['data']['options'] = collect($item['data']['options'])->shuffle()
                    ->values()
                    ->all();
            }

            return $item;
        }, $schema);
    }
}

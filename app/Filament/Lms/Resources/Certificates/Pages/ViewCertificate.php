<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Certificates\Pages;

use App\Filament\Lms\Resources\Certificates\CertificateResource;
use App\Filament\Lms\Resources\Quizzes\QuizResource;
use App\Models\Lms\Certificate;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificate extends ViewRecord
{
    protected static string $resource = CertificateResource::class;

    // public function getSubheading(): ?string
    // {
    //     return __('Custom Page Subheading');
    // }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewAttempt')
                ->label('Recertify')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->visible(fn (Certificate $certificate): bool => (bool) $certificate->quiz_attempt_id)
                ->url(function (Certificate $certificate): ?string {
                    $attempt = $certificate->attempt->quiz_id; // or resolve via model: QuizAttempt::find($record->quiz_attempt_id)
                    if (! $attempt) {
                        return null;
                    }

                    return QuizResource::getUrl(
                        'view',
                        ['record' => $attempt], // can pass model or route key (id/uuid)
                        panel: 'lms',
                    );
                })
                ->openUrlInNewTab(true),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\QuizAttemptResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuizAttempt extends ViewRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

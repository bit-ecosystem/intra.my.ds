<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\QuizAttempts\Pages;

use App\Filament\Lms\Resources\QuizAttempts\QuizAttemptResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuizAttempt extends EditRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

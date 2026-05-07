<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\QuizAttemptResource;
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

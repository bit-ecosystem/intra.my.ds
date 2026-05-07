<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\QuizAttemptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuizAttempts extends ListRecords
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\QuizAttempts\Pages;

use App\Filament\Lms\Resources\QuizAttempts\QuizAttemptResource;
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

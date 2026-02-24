<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Quizzes\Pages;

use App\Filament\Lms\Resources\QuizAttempts\QuizAttemptResource;
use App\Filament\Lms\Resources\Quizzes\QuizResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuiz extends ViewRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('openDynamicForm')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn ($record): string => QuizAttemptResource::getUrl('create', ['form_id' => $record->id]))
                // ->openUrlInNewTab() // optional
                ->label('Do Quiz'),

        ];
    }
}

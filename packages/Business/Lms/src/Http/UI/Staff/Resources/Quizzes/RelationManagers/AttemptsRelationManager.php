<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\RelationManagers;

use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\QuizAttemptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';

    protected static ?string $relatedResource = QuizAttemptResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts;

use BackedEnum;
use Bites\Business\Lms\Entities\QuizAttempt;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages\CreateQuizAttempt;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages\EditQuizAttempt;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages\ViewQuizAttempt;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Pages\ListQuizAttempts;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Schemas\QuizAttemptForm;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Schemas\QuizAttemptInfolist;
use Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Tables\QuizAttemptsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-quiz-attempt';

    protected static string|UnitEnum|null $navigationGroup = 'Classroom';

    protected static ?string $modelLabel = 'Attempts';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return QuizAttemptForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return QuizAttemptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizAttemptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuizAttempts::route('/'),
            'create' => CreateQuizAttempt::route('/create'),
            'view' => ViewQuizAttempt::route('/{record}'),
            'edit' => EditQuizAttempt::route('/{record}/edit'),
        ];
    }
}

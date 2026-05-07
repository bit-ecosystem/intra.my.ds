<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes;

use BackedEnum;
use Bites\Business\Lms\Entities\Quiz;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Pages\CreateQuiz;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Pages\EditQuiz;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Pages\ListQuizzes;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Pages\ViewQuiz;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Schemas\QuizForm;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Schemas\QuizInfolist;
use Bites\Business\Lms\Http\UI\Staff\Resources\Quizzes\Tables\QuizzesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return QuizForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizzesTable::configure($table);
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
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'view' => ViewQuiz::route('/{record}'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}

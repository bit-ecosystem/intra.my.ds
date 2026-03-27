<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Quizzes;

use App\Filament\Lms\Resources\Quizzes\Pages\CreateQuiz;
use App\Filament\Lms\Resources\Quizzes\Pages\EditQuiz;
use App\Filament\Lms\Resources\Quizzes\Pages\ListQuizzes;
use App\Filament\Lms\Resources\Quizzes\Pages\ViewQuiz;
use App\Filament\Lms\Resources\Quizzes\Schemas\QuizForm;
use App\Filament\Lms\Resources\Quizzes\Schemas\QuizInfolist;
use App\Filament\Lms\Resources\Quizzes\Tables\QuizzesTable;
use BackedEnum;
use Bites\Kbm\Lms\Models\Quiz;
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

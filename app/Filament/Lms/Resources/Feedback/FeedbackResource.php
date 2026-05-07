<?php

namespace App\Filament\Lms\Resources\Feedback;

use App\Filament\Lms\Resources\Feedback\Pages\CreateFeedback;
use App\Filament\Lms\Resources\Feedback\Pages\EditFeedback;
use App\Filament\Lms\Resources\Feedback\Pages\ListFeedback;
use App\Filament\Lms\Resources\Feedback\Pages\ViewFeedback;
use App\Filament\Lms\Resources\Feedback\Schemas\FeedbackForm;
use App\Filament\Lms\Resources\Feedback\Schemas\FeedbackInfolist;
use App\Filament\Lms\Resources\Feedback\Tables\FeedbackTable;
use BackedEnum;
use Bites\Business\Lms\Entities\Feedback;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FeedbackForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedbackInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedbackTable::configure($table);
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
            'index' => ListFeedback::route('/'),
            'create' => CreateFeedback::route('/create'),
            'view' => ViewFeedback::route('/{record}'),
            'edit' => EditFeedback::route('/{record}/edit'),
        ];
    }
}

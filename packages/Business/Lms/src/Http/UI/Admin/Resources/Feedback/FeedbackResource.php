<?php

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Feedback;

use BackedEnum;
use Bites\Business\Lms\Entities\Feedback;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Pages\CreateFeedback;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Pages\EditFeedback;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Pages\ListFeedback;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Pages\ViewFeedback;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Schemas\FeedbackForm;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Schemas\FeedbackInfolist;
use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Tables\FeedbackTable;
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

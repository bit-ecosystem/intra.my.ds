<?php

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations;

use BackedEnum;
use Bites\Business\Lms\Entities\Evaluation;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Pages\CreateEvaluation;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Pages\EditEvaluation;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Pages\ListEvaluations;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Pages\ViewEvaluation;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationForm;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationInfolist;
use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Tables\EvaluationsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EvaluationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EvaluationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationsTable::configure($table);
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
            'index' => ListEvaluations::route('/'),
            'create' => CreateEvaluation::route('/create'),
            'view' => ViewEvaluation::route('/{record}'),
            'edit' => EditEvaluation::route('/{record}/edit'),
        ];
    }
}

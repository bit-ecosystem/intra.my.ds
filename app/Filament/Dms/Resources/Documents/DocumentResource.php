<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Documents;

use App\Filament\Dms\Resources\Documents\Pages\CreateDocument;
use App\Filament\Dms\Resources\Documents\Pages\EditDocument;
use App\Filament\Dms\Resources\Documents\Pages\ListDocuments;
use App\Filament\Dms\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Dms\Resources\Documents\Tables\DocumentsTable;
use App\Models\Dms\Document;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-book-open-02';

    public static function getHeader(): ?string
    {
        return 'This resource manages all your IT ecosystem components.';
    }

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
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
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}

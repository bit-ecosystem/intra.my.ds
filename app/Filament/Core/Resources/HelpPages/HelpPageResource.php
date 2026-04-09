<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages;

use App\Filament\Core\Resources\HelpPages\Pages\CreateHelpPage;
use App\Filament\Core\Resources\HelpPages\Pages\EditHelpPage;
use App\Filament\Core\Resources\HelpPages\Pages\ListHelpPages;
use App\Filament\Core\Resources\HelpPages\Pages\ViewHelpPage;
use App\Filament\Core\Resources\HelpPages\Schemas\HelpPageForm;
use App\Filament\Core\Resources\HelpPages\Schemas\HelpPageInfolist;
use App\Filament\Core\Resources\HelpPages\Tables\HelpPagesTable;
use BackedEnum;
use Bites\Service\Models\HelpPage;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HelpPageResource extends Resource
{
    protected static ?string $model = HelpPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Links';

    public static function form(Schema $schema): Schema
    {
        return HelpPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HelpPagesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HelpPageInfolist::configure($schema);
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
            'index' => ListHelpPages::route('/'),
            'create' => CreateHelpPage::route('/create'),
            'view' => ViewHelpPage::route('/{record}'),
            'edit' => EditHelpPage::route('/{record}/edit'),
        ];
    }
}

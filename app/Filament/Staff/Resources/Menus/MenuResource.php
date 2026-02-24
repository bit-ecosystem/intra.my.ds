<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Menus;

use App\Filament\Staff\Resources\Menus\Pages\CreateMenu;
use App\Filament\Staff\Resources\Menus\Pages\EditMenu;
use App\Filament\Staff\Resources\Menus\Pages\ListMenus;
use App\Filament\Staff\Resources\Menus\Schemas\MenuForm;
use App\Filament\Staff\Resources\Menus\Tables\MenusTable;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?int $navigationSort = 31;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-r-menu';

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
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
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}

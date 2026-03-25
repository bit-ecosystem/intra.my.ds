<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\Staff;

use App\Filament\Hrm\Resources\Staff\Pages\CreateStaff;
use App\Filament\Hrm\Resources\Staff\Pages\EditStaff;
use App\Filament\Hrm\Resources\Staff\Pages\ListStaff;
use App\Filament\Hrm\Resources\Staff\Schemas\StaffForm;
use App\Filament\Hrm\Resources\Staff\Tables\StaffTable;
use Bites\Hrm\Models\Staff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-staff';

    public static function getGloballySearchableAttributes(): array
    {
        return ['staff_number', 'name', 'jobPosition.title', 'orgUnit.code'];
    }

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::configure($table);
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
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\DeviceCodes;

use BackedEnum;
use Bites\Organization\Identity\Resources\DeviceCodes\Pages\CreateDeviceCode;
use Bites\Organization\Identity\Resources\DeviceCodes\Pages\EditDeviceCode;
use Bites\Organization\Identity\Resources\DeviceCodes\Pages\ListDeviceCodes;
use Bites\Organization\Identity\Resources\DeviceCodes\Schemas\DeviceCodeForm;
use Bites\Organization\Identity\Resources\DeviceCodes\Tables\DeviceCodesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Laravel\Passport\DeviceCode;
use UnitEnum;

class DeviceCodeResource extends Resource
{
    protected static ?string $model = DeviceCode::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-devices-code';

    protected static string|UnitEnum|null $navigationGroup = 'Codes';

    // protected static ?string $modelLabel = 'Device Codes';

    public static function form(Schema $schema): Schema
    {
        return DeviceCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceCodesTable::configure($table);
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
            'index' => ListDeviceCodes::route('/'),
            'create' => CreateDeviceCode::route('/create'),
            'edit' => EditDeviceCode::route('/{record}/edit'),
        ];
    }
}

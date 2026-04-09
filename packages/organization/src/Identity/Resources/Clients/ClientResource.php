<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Clients;

use BackedEnum;
use Bites\Organization\Identity\Resources\Clients\Pages\CreateClient;
use Bites\Organization\Identity\Resources\Clients\Pages\EditClient;
use Bites\Organization\Identity\Resources\Clients\Pages\ListClients;
use Bites\Organization\Identity\Resources\Clients\Schemas\ClientForm;
use Bites\Organization\Identity\Resources\Clients\Tables\ClientsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Laravel\Passport\Client;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-sw-app';

    protected static ?string $modelLabel = 'Client Apps';

    public static function form(Schema $schema): Schema
    {
        return ClientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientsTable::configure($table);
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
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }
}

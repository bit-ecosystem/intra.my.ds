<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Requests;

use App\Filament\Erp\Resources\Workflow\Requests\Pages\CreateRequest;
use App\Filament\Erp\Resources\Workflow\Requests\Pages\EditRequest;
use App\Filament\Erp\Resources\Workflow\Requests\Pages\ListRequests;
use App\Filament\Erp\Resources\Workflow\Requests\Schemas\RequestForm;
use App\Filament\Erp\Resources\Workflow\Requests\Tables\RequestsTable;
use BackedEnum;
use Bites\Workflow\Models\Request;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?int $navigationSort = 55;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-w-request';

    protected static string|UnitEnum|null $navigationGroup = 'Process Management';

    public static function form(Schema $schema): Schema
    {
        return RequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestsTable::configure($table);
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
            'index' => ListRequests::route('/'),
            'create' => CreateRequest::route('/create'),
            'edit' => EditRequest::route('/{record}/edit'),
        ];
    }
}

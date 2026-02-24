<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers\Pages;

use App\Filament\Core\Resources\RoleMappers\RoleMapperResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoleMappers extends ListRecords
{
    protected static string $resource = RoleMapperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

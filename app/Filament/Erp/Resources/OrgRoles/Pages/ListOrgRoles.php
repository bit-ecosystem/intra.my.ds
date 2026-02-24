<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\OrgRoles\Pages;

use App\Filament\Erp\Resources\OrgRoles\OrgRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrgRoles extends ListRecords
{
    protected static string $resource = OrgRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

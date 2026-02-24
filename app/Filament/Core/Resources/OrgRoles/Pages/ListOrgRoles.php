<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\OrgRoles\Pages;

use App\Filament\Core\Resources\OrgRoles\OrgRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrgRoles extends ListRecords
{
    protected static string $resource = OrgRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            // \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class),
        ];
    }
}

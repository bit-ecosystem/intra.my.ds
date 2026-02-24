<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\OrgRoles\Pages;

use App\Filament\Core\Resources\OrgRoles\OrgRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgRole extends CreateRecord
{
    protected static string $resource = OrgRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class),
        ];
    }
}

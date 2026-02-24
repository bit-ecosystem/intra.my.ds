<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\OrgRoles\Pages;

use App\Filament\Actions\GenerateWithAIAction;
use App\Filament\Erp\Resources\OrgRoles\OrgRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgRole extends CreateRecord
{
    protected static string $resource = OrgRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            GenerateWithAIAction::make(),
        ];
    }
}

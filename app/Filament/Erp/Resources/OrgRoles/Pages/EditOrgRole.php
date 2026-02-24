<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\OrgRoles\Pages;

use App\Filament\Erp\Resources\OrgRoles\OrgRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrgRole extends EditRecord
{
    protected static string $resource = OrgRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

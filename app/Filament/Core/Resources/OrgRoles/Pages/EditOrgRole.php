<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\OrgRoles\Pages;

use App\Filament\Core\Resources\OrgRoles\OrgRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrgRole extends EditRecord
{
    protected static string $resource = OrgRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            // \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class, $this->record),

        ];
    }
}

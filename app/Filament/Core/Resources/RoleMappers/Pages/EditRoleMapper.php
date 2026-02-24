<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers\Pages;

use App\Filament\Core\Resources\RoleMappers\RoleMapperResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoleMapper extends EditRecord
{
    protected static string $resource = RoleMapperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

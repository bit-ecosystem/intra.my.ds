<?php

namespace App\Filament\Core\Resources\Permissions\Pages;

use App\Filament\Core\Resources\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers\Pages;

use App\Filament\Core\Resources\RoleMappers\RoleMapperResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoleMapper extends CreateRecord
{
    protected static string $resource = RoleMapperResource::class;
}

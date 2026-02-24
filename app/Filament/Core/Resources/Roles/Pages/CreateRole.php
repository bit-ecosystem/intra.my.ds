<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\Roles\Pages;

use App\Filament\Core\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
}

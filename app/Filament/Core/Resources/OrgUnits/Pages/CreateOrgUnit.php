<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\OrgUnits\Pages;

use App\Filament\Core\Resources\OrgUnits\OrgUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgUnit extends CreateRecord
{
    protected static string $resource = OrgUnitResource::class;
}

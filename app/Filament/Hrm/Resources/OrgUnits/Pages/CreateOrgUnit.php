<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\OrgUnits\Pages;

use App\Filament\Hrm\Resources\OrgUnits\OrgUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgUnit extends CreateRecord
{
    protected static string $resource = OrgUnitResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\WorkforcePlans\Pages;

use App\Filament\Erp\Resources\WorkforcePlans\WorkforcePlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkforcePlan extends CreateRecord
{
    protected static string $resource = WorkforcePlanResource::class;
}

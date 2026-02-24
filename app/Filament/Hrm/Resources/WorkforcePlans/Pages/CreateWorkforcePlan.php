<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\WorkforcePlans\Pages;

use App\Filament\Hrm\Resources\WorkforcePlans\WorkforcePlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkforcePlan extends CreateRecord
{
    protected static string $resource = WorkforcePlanResource::class;
}

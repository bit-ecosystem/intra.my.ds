<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\Staff\Pages;

use App\Filament\Hrm\Resources\Staff\StaffResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobPositions\Pages;

use App\Filament\Hrm\Resources\JobPositions\JobPositionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobPosition extends CreateRecord
{
    protected static string $resource = JobPositionResource::class;
}

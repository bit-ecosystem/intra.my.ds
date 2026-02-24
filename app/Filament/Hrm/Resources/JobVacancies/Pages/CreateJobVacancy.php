<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobVacancies\Pages;

use App\Filament\Hrm\Resources\JobVacancies\JobVacancyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobVacancy extends CreateRecord
{
    protected static string $resource = JobVacancyResource::class;
}

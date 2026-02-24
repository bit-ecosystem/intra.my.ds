<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobDescriptionTemplates\Pages;

use App\Filament\Hrm\Resources\JobDescriptionTemplates\JobDescriptionTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobDescriptionTemplate extends CreateRecord
{
    protected static string $resource = JobDescriptionTemplateResource::class;
}

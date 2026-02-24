<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobDescriptionTemplates\Pages;

use App\Filament\Hrm\Resources\JobDescriptionTemplates\JobDescriptionTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobDescriptionTemplates extends ListRecords
{
    protected static string $resource = JobDescriptionTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

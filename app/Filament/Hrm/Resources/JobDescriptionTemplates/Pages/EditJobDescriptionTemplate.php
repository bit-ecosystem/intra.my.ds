<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobDescriptionTemplates\Pages;

use App\Filament\Hrm\Resources\JobDescriptionTemplates\JobDescriptionTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobDescriptionTemplate extends EditRecord
{
    protected static string $resource = JobDescriptionTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

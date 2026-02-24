<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\WorkforcePlans\Pages;

use App\Filament\Hrm\Resources\WorkforcePlans\WorkforcePlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkforcePlan extends EditRecord
{
    protected static string $resource = WorkforcePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

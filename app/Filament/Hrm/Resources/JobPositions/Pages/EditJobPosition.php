<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobPositions\Pages;

use App\Filament\Hrm\Resources\JobPositions\JobPositionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobPosition extends EditRecord
{
    protected static string $resource = JobPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

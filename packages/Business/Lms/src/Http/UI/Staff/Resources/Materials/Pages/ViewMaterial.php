<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Materials\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Materials\MaterialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterial extends ViewRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

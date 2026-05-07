<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Modules\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Modules\ModuleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewModule extends ViewRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

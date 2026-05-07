<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;
use Bites\Service\Concerns\HasHelp;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    use HasHelp;

    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

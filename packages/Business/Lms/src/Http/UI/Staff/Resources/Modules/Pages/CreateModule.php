<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Modules\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Modules\ModuleResource;
use Bites\Service\Concerns\HasHelp;
use Filament\Resources\Pages\CreateRecord;

class CreateModule extends CreateRecord
{
    use HasHelp;

    protected static string $resource = ModuleResource::class;
}

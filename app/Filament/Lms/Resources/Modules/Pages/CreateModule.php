<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Modules\Pages;

use App\Filament\Lms\Resources\Modules\ModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Materials\Pages;

use App\Filament\Lms\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
}

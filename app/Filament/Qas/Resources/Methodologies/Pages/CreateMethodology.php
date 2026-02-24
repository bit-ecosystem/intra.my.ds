<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Pages;

use App\Filament\Qas\Resources\Methodologies\MethodologyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMethodology extends CreateRecord
{
    protected static string $resource = MethodologyResource::class;
}

<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Pages;

use App\Filament\Core\Resources\HelpPages\HelpPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHelpPage extends CreateRecord
{
    protected static string $resource = HelpPageResource::class;
}

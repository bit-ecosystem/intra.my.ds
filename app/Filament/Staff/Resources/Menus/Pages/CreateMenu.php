<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Menus\Pages;

use App\Filament\Staff\Resources\Menus\MenuResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}

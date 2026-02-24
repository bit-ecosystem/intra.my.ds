<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Pages;

use App\Filament\Qas\Resources\Methodologies\MethodologyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMethodology extends EditRecord
{
    protected static string $resource = MethodologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

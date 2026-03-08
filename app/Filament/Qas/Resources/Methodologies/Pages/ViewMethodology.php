<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Pages;

use App\Filament\Qas\Resources\Methodologies\MethodologyResource;
use App\Filament\Qas\Resources\RunInitiatives\RunInitiativeResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewMethodology extends ViewRecord
{
    protected static string $resource = MethodologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('openDynamicForm')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn ($record): string => RunInitiativeResource::getUrl('create', [
                    'what' => $record->id,
                    'who' => Auth::id(),
                ]))
                // ->openUrlInNewTab() // optional
                ->label('Run Initiative'),
        ];
    }
}

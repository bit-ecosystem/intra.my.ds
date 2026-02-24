<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RunInitiativeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('methodology.id')
                    ->label('Methodology'),
                TextEntry::make('initiator.name')
                    ->label('Initiator')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('inputs')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('outputs')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('started_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

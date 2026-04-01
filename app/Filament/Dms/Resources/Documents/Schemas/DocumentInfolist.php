<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Documents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('orgUnit.name')
                    ->label('Org unit')
                    ->placeholder('-'),
                TextEntry::make('document_type_id')
                    ->numeric(),
                TextEntry::make('classification_id')
                    ->numeric(),
                TextEntry::make('owner_staff_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('parent_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('code'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('file_path')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('effective_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('retired_at')
                    ->dateTime()
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

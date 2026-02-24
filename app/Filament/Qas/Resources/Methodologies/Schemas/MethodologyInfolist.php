<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MethodologyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('methodology'),
                TextEntry::make('purpose')
                    ->placeholder('-'),
                TextEntry::make('brief_explanation')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('needs_form')
                    ->boolean(),
                IconEntry::make('needs_report')
                    ->boolean(),
                TextEntry::make('typical_record_type')
                    ->placeholder('-'),
                TextEntry::make('example_template_name')
                    ->placeholder('-'),
                TextEntry::make('external_url')
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

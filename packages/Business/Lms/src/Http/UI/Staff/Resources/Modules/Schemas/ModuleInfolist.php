<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Modules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ModuleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('slug'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                // TextEntry::make('order_index')
                //     ->numeric(),
                TextEntry::make('estimated_duration_minutes')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('validity_months')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('certificate_template')
                    ->placeholder('-')
                    ->columnSpanFull(),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
            ]);
    }
}

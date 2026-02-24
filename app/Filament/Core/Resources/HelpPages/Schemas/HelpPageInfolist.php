<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HelpPageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('content')
                    ->html()
                    ->extraAttributes(['class' => 'document-content'])
                    ->columnSpanFull(),
            ]);
    }
}

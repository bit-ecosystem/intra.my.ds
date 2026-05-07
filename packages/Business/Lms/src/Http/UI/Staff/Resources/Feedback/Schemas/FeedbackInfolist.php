<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedbackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('evaluation.name')
                    ->label('Evaluation'),
                TextEntry::make('data')
                    ->columnSpanFull(),
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                TextEntry::make('for_staff')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('by_staff')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('module.title')
                    ->label('Module'),
                TextEntry::make('started_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('time_taken')
                    ->numeric()
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

<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Schemas;

use Filament\Infolists\Components;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // =====================
                // Main Information
                // =====================
                Section::make('General Information')
                    ->description('Core details of the record')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('code')
                                    ->label('Code')
                                    ->badge()
                                    ->color('gray')
                                    ->copyable(),

                                Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state) => match ($state) {
                                        'active' => 'success',
                                        'draft' => 'warning',
                                        'archived' => 'gray',
                                        default => 'secondary',
                                    }),

                                Components\TextEntry::make('published_at')
                                    ->label('Published At')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ]),

                        Components\TextEntry::make('title')
                            ->label('Title')
                            ->inlineLabel()
                            ->size(Enums\TextSize::Large)
                            ->weight(Enums\FontWeight::SemiBold)
                            ->columnSpanFull(),

                        Components\TextEntry::make('description')
                            ->label('Description')
                            ->inlineLabel()
                            ->markdown()
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // =====================
                // Audit & Metadata
                // =====================
                Section::make('Audit Information')
                    ->description('System-generated timestamps')
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                        ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->since(),

                                Components\TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->since(),

                                Components\TextEntry::make('published_at')
                                    ->label('Published At')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->since(),
                            ]),
                    ]),
            ]);
    }
}

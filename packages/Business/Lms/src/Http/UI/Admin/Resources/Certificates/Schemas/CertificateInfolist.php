<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Certificates\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Top Title Section
                Section::make()
                    ->columnSpanFull()
                    ->heading('Certificate of Achievement')
                    ->description('This certificate is proudly presented to')
                    ->headerActions([])
                    ->schema([
                        Section::make()
                            ->columns(4)
                            ->schema([
                                ImageEntry::make('header_image_url')
                                    ->hiddenLabel()
                                    ->imageHeight(200)
                                    ->circular()
                                    ->defaultImageUrl(function ($record): ?string {
                                        $filename = optional($record->staff)->staff_old_number;

                                        return $filename
                                            ? sprintf('http://10.40.3.41:8080/%s.jpg', $filename)
                                            : null;
                                    })->columnSpan(1),
                                Section::make()
                                    ->schema([
                                        TextEntry::make('staff.name')
                                            ->color('primary')
                                            ->size('lg')
                                            ->weight('bold')
                                            ->alignCenter(),
                                        TextEntry::make('staff.staff_number')
                                            ->label('')
                                            ->placeholder('-')
                                            ->size('lg')
                                            ->alignCenter()
                                            ->columnSpanFull(),
                                    ])->columnSpan(3),
                            ]),
                        Section::make('Certificate Details')
                            ->description('Issued information and validity')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('certificate_number')
                                    ->label('Certificate No.')
                                    ->weight('medium')
                                    ->inlineLabel(),

                                TextEntry::make('issued_at')
                                    ->label('Issued On')
                                    ->date()
                                    ->inlineLabel(),

                                TextEntry::make('attempt.quiz.name')
                                    ->label('Quiz / Assessment')
                                    ->placeholder('-')
                                    ->inlineLabel(),

                                TextEntry::make('expires_at')
                                    ->label('Valid Until')
                                    ->placeholder('-')
                                    ->date() // keeps your date formatting
                                    ->inlineLabel()
                                    ->color(function ($state): string {
                                        if (blank($state)) {
                                            return 'gray';
                                        }

                                        $expiry = $state instanceof Carbon ? $state : Carbon::parse($state);
                                        if (now()->greaterThan($expiry)) {
                                            return 'danger';
                                        }

                                        $days = now()->diffInDays($expiry, false);
                                        if ($days <= 14) {
                                            return 'warning';
                                        }

                                        return 'primary';
                                    }),

                            ]),

                    ])
                    ->collapsible(false),

                // Details Section

                // Signature Section
                // Section::make()
                // ->columns(1)
                // ->schema([
                //     TextEntry::make('')
                //         ->label('')
                //         ->state('____________________________')
                //         ->alignCenter(),

                //     TextEntry::make('')
                //         ->label('')
                //         ->state('Authorized Signature')
                //         ->alignCenter()
                //         ->size('sm')
                //         ->color('gray'),
                // ])
                // ->collapsible(false),
            ]);
    }
}

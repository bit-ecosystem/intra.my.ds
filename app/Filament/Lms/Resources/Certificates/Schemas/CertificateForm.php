<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Certificates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required(),
                Select::make('for_staff')
                    ->relationship('staff', 'name')
                    ->required(),
                TextInput::make('quiz_attempt_id')
                    ->required()
                    ->numeric(),
                TextInput::make('certificate_number')
                    ->required(),
                TextInput::make('title'),
                DateTimePicker::make('issued_at')
                    ->required(),

                DateTimePicker::make('expires_at'),
                TextInput::make('status')
                    ->required()
                    ->default('valid'),
            ]);
    }
}

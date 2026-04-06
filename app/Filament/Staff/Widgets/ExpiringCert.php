<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use Bites\Knowledge\Learning\Certificate;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExpiringCert extends TableWidget
{
    protected static ?string $heading = 'Certificates Expiring';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        // dd(Auth::user()->staff);
        return $table
            ->query(fn (): Builder => Certificate::query()->where('status', 'valid')
                ->whereNotNull('expires_at')
                ->where('for_staff', Auth::user()->staff->id)
                ->where('expires_at', '<=', now()->addMonth())
                ->orderBy('expires_at', 'asc'))
            ->columns([
                TextColumn::make('module.title'),
                TextColumn::make('certificate_number')
                    ->color(fn ($record): string => $record->expires_at->isPast() ? 'danger' : 'info'),
                TextColumn::make('issued_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->since()
                    ->dateTimeTooltip()
                    ->color(fn ($record): string => $record->expires_at->isPast() ? 'danger' : 'info')
                    ->sortable(),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->recordUrl(
                fn (Model $record): string => route('filament.lms.resources.certificates.view', ['record' => $record]),
            );
    }
}

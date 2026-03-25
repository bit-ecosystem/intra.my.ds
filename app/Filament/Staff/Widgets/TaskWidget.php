<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use App\Filament\Lms\Resources\Certificates\CertificateResource;
use App\Models\Lms\Certificate;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TaskWidget extends TableWidget
{
    protected int|string|array $columnSpan = 3;

    protected function getHeading(): ?string
    {
        return __('Credentials');
    }

    protected function getDescription(): ?string
    {
        return __('Certificates/Licenses needing action');
    }

    // If you still want to keep your resource mapping for recordUrl
    protected array $map = [
        Certificate::class => [CertificateResource::class, 'lms'],
    ];

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $staffId = $user?->staff?->id;

        // Defensive: if user has no staff, return an empty builder
        $query = Certificate::query()
            ->when($staffId, fn (Builder $q) => $q->where('for_staff', $staffId))
            ->whereBetween('expires_at', [now(), now()->addDays(14)])
            ->latest('expires_at')
            ->with(['module', 'examiner', 'staff']); // eager-load what you display

        return $table
            ->query($query)
            ->recordUrl(function (Certificate $certificate) {
                [$resourceClass, $panelId] = $this->map[Certificate::class] ?? [null, null];
                if (! $resourceClass || ! $panelId) {
                    return null;
                }

                return $resourceClass::getUrl('view', [
                    'record' => $certificate,
                ], panel: $panelId);
            })
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->badge()
                    ->color('primary')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('certificate_number')
                    ->label('Certificate #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('module.title')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('issued_at')
                    ->label('Issued')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->date('Y-m-d')
                    ->since() // “in 10 days”
                    ->icon(fn ($record): ?string => now()->greaterThan($record->expires_at) ? 'heroicon-o-exclamation-triangle' : null)
                    ->color(fn ($record): string => now()->greaterThan($record->expires_at) ? 'danger' : 'warning')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'expired' => 'danger',
                        'revoked' => 'gray',
                        default => 'secondary',
                    })
                    ->sortable(),
            ]);
    }
}

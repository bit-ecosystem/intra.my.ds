<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Schemas;

use App\Services\FormFormatBuilder;
use Filament\Schemas;
use Filament\Schemas\Schema;

class RunInitiativeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make()
                    ->label('')

                    ->schema(function (callable $get): array {
                        $raw = $get('form_schema');

                        // Accept JSON string or array
                        $schema = is_string($raw) ? json_decode($raw, true) : ($raw ?? []);

                        // Build the Filament components from your builder
                        $components = app(FormFormatBuilder::class)->build($schema);

                        // IMPORTANT: Return the array of components for Filament to render.
                        return is_array($components) ? $components : [];
                    })
                    ->columnSpanFull(),
            ]);
    }
}

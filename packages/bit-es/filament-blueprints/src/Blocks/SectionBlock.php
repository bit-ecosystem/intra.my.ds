<?php

declare(strict_types=1);

namespace Bites\FilamentBlueprints\Blocks;

use Bites\FilamentBlueprints\BlockRegistry;
use Bites\FilamentBlueprints\Concerns\AppliesProps;
use Bites\FilamentBlueprints\Contracts\BlockContract;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

final class SectionBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'section';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
            ->label('Section')
            ->schema([
                TextInput::make('label')->required(),
                KeyValue::make('props')->label('Section props (method => value)'),
                Builder::make('children')->label('Children'),
            ]);
    }

    public function decode(array $payload, BlockRegistry $registry): Section
    {
        $label = $payload['label'] ?? 'Section';
        $props = (array) ($payload['props'] ?? []);
        $children = $registry->decodeBuilder($payload['children'] ?? []);

        $section = Section::make($label)->schema($children);

        $allowed = [
            'collapsible' => 'collapsible',
            'collapsed' => 'collapsed',
            'columns' => 'columns',
            'description' => 'description',
            'icon' => 'icon',
            'columnSpan' => 'columnSpan',
        ];

        return $this->applyProps($section, $props, $allowed);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

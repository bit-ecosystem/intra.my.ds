<?php

namespace Bites\FilamentBlueprints\Blocks;


use Bites\FilamentBlueprints\BlockRegistry;
use Bites\FilamentBlueprints\Contracts\BlockContract;
use Bites\FilamentBlueprints\Traits\AppliesProps;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;


final class GridBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'grid';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
            ->label('Grid')
            ->schema([
                KeyValue::make('columns')->label('Columns (breakpoint => count)'),
                KeyValue::make('props')->label('Grid props'),
                Builder::make('children')->label('Children'),
            ]);
    }

    public function decode(array $payload, BlockRegistry $registry): Grid
    {
        $columns  = (array) ($payload['columns'] ?? []);
        $props    = (array) ($payload['props'] ?? []);
        $children = $registry->decodeBuilder($payload['children'] ?? []);

        $component = Grid::make()->schema($children);

        if (! empty($columns)) {
            // columns accepts array map or int – pass as is, after coercion
            $component->columns(array_map(fn($v) => $this->coerceValue($v), $columns));
        }

        $allowed = [
            'columnSpan' => 'columnSpan',
            'visible' => 'visible',
            'hidden' => 'hidden',
        ];

        return $this->applyProps($component, $props, $allowed);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

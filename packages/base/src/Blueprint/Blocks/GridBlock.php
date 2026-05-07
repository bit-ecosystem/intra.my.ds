<?php

declare(strict_types=1);

namespace Bites\FilamentBlueprints\Blocks;

use Bites\Base\Blueprint\BlockRegistry;
use Bites\Base\Blueprint\Concerns\AppliesProps;
use Bites\Base\Blueprint\Contracts\BlockContract;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
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
        $columns = (array) ($payload['columns'] ?? []);
        $props = (array) ($payload['props'] ?? []);
        $children = $registry->decodeBuilder($payload['children'] ?? []);

        $grid = Grid::make()->schema($children);

        if ($columns !== []) {
            // columns accepts array map or int – pass as is, after coercion
            $grid->columns(array_map(fn ($v): mixed => $this->coerceValue($v), $columns));
        }

        $allowed = [
            'columnSpan' => 'columnSpan',
            'visible' => 'visible',
            'hidden' => 'hidden',
        ];

        return $this->applyProps($grid, $props, $allowed);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

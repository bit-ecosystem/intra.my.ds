<?php

namespace Bites\FilamentBlueprints\Contracts;

use Bites\FilamentBlueprints\BlockRegistry;
use Filament\Forms\Components\Builder\Block;

interface BlockContract
{
    public static function name(): string;

    public function palette(): Block;

    public function decode(array $payload, BlockRegistry $registry): mixed;

    public function encode(array $payload): array;
}

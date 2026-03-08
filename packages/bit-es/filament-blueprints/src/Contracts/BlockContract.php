<?php

namespace Bites\FilamentBlueprints\Contracts;

use Filament\Forms\Components\Builder\Block;

interface BlockContract
{
    public static function name(): string;

    public function palette(): Block;

    public function decode(array $payload, \Bites\FilamentBlueprints\BlockRegistry $registry): mixed;

    public function encode(array $payload): array;
}
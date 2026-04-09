<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Contracts;

use Bites\Base\Blueprint\BlockRegistry;
use Filament\Forms\Components\Builder\Block;

interface BlockContract
{
    public static function name(): string;

    public function palette(): Block;

    public function decode(array $payload, BlockRegistry $registry): mixed;

    public function encode(array $payload): array;
}

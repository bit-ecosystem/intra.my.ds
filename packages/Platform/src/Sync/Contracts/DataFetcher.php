<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Contracts;

interface DataFetcher
{
    public function fetch(array $config): array;
}

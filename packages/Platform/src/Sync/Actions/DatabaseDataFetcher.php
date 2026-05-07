<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Actions;

use Bites\Platform\Sync\Contracts\DataFetcher;
use Illuminate\Support\Facades\DB;

class DatabaseDataFetcher implements DataFetcher
{
    public function fetch(array $config): array
    {
        return DB::connection($config['connection'])
            ->select($config['query'], $config['bindings'] ?? []);
    }
}

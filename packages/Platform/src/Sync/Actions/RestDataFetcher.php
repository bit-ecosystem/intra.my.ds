<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Actions;

use Bites\Platform\Sync\Contracts\DataFetcher;
use Illuminate\Support\Facades\Http;

class RestDataFetcher implements DataFetcher
{
    public function fetch(array $config): array
    {
        return Http::withHeaders($config['headers'] ?? [])
            ->get($config['url'], $config['query'] ?? [])
            ->json();
    }
}

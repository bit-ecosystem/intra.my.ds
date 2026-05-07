<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Observers;

use Bites\Platform\Sync\Entities\ApiData;
use Bites\Platform\Sync\Jobs\ProcessApiDataJob;
use Bites\Platform\Sync\Services\ProcessingService;

class ApiDataObserver
{
    public function created(ApiData $data): void
    {
        app(ProcessingService::class)->process($data);
    }

    public function updated(ApiData $data): void
    {

        if (in_array($data->status, ['processed', 'failed'], true)) {
            return;
        }

        // ✅ Respect config: async (queue) OR sync (immediate)
        if (config('bites.sync.queue', true)) {
            ProcessApiDataJob::dispatch($data);

            return;
        }

        // ✅ Sync processing fallback
        app(ProcessingService::class)->process($data);
    }
}

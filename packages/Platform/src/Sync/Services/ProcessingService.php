<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Services;

use Bites\Platform\Sync\Entities\ApiData;

class ProcessingService
{
    public function process(ApiData $data): void
    {
        try {
            app(MappingService::class)->run($data);
            $data->markProcessed();
        } catch (\Throwable $throwable) {
            $data->markFailed($throwable->getMessage());
            throw $throwable;
        }
    }
}

<?php

declare(strict_types=1);

namespace Bites\Service\Observers;

use Bites\Service\Jobs\SeedStaffFromApiData;
use Bites\Service\Models\ApiData;

class ApiDataObserver
{
    /**
     * Handle the ApiData "created" event.
     */
    public function created(ApiData $apiData): void
    {
        if (($apiData->source ?? '') !== 'MWSQLDBQ03.my.ds.amkor.com/ecology') {
            return;
        }

        dispatch(new SeedStaffFromApiData($apiData->id))
            ->onQueue('weaver-seed');
    }
}

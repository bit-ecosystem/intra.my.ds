<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ApiData;

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

        dispatch(new \App\Jobs\SeedStaffFromApiData($apiData->id))
            ->onQueue('weaver-seed');
    }
}

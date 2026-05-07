<?php

declare(strict_types=1);

namespace Bites\Business\Lms;

use Bites\Platform\Utility\ApiFetchCommand;
use Bites\Support\Helper\MergeConfigAction;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Laravel\Events\Import\Synchronized;
use Bites\Platform\Auth\Listeners\SyncLdap;
use Illuminate\Support\Facades\Event;

class LmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}

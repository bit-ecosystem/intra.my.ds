<?php

declare(strict_types=1);

namespace Bites\Platform;

use Bites\Platform\Auth\Listeners\SyncLdap;
use Bites\Platform\Utility\ApiFetchCommand;
use Bites\Platform\Utility\MergeConfigAction;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Laravel\Events\Import\Synchronized;

class PlatformServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // LDAP Auth
        $this->mergeConfigFrom(__DIR__.'/../config/ldap.php', 'ldap');
        $this->app->make(MergeConfigAction::class)->execute(path: __DIR__.'/../config/rimba.php', key: 'rimba');    
        //  dd(config('rimba'));
        Event::listen(Synchronized::class, [
            SyncLdap::class,
            'handle',
        ]);
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ApiFetchCommand::class,
            ]);
        }
    }
}

<?php

declare(strict_types=1);

namespace Bites\Business\Lms;

use Bites\Platform\Utility\MergeConfigAction;
use Illuminate\Support\ServiceProvider;

class LmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->make(MergeConfigAction::class)->execute(path: __DIR__.'/../config/rimba.php', key: 'rimba');
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

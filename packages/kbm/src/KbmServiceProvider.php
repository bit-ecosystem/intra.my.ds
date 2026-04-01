<?php

declare(strict_types=1);

namespace Bites\Kbm;

use Illuminate\Support\ServiceProvider;

class KbmServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

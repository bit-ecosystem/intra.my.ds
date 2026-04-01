<?php

declare(strict_types=1);

namespace Bites\Oum;

use Illuminate\Support\ServiceProvider;

class OumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

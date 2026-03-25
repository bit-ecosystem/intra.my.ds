<?php

namespace Bites\Kbm;

use Illuminate\Support\ServiceProvider;

class KbmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

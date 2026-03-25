<?php

namespace Bites\Oum;

use Illuminate\Support\ServiceProvider;

class OumServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

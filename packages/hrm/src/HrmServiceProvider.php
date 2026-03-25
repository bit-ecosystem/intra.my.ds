<?php

namespace Bites\Hrm;

use Illuminate\Support\ServiceProvider;

class HrmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

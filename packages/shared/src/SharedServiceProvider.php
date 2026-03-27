<?php

namespace Bites\Shared;

use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/../config/shared.php', 'shared');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'shared');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bites-shared');
    }
}

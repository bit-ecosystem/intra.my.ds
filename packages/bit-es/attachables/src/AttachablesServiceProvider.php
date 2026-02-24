<?php

declare(strict_types=1);

namespace Bites\Attachables;

use Illuminate\Support\ServiceProvider;

class AttachablesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register view namespace first
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bites');
    }

    public function register(): void
    {
        //
    }
}

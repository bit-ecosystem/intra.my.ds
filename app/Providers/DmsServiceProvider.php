<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->mergeConfigFrom(__DIR__ . '/../config/bites.php', 'dms');
        $this->app->singleton(\App\Services\EmbeddingService::class);
        $this->app->singleton(\App\Services\RagService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // $this->publishes([
        //     __DIR__ . '/../config/dms.php' => config_path('dms.php'),
        // ], 'dms-config');

        // Document::observe(DocumentObserver::class);

        // load web routes for chat proxy and UI
        // $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // load package views (filament chat view)
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dms');
    }
}

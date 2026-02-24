<?php

declare(strict_types=1);

namespace Bites\WeaverSSO;

use Illuminate\Support\ServiceProvider;

class WeaverSSOServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // Merge default config
        $this->mergeConfigFrom(__DIR__.'/../config/weaversso.php', 'weaversso');
    }

    public function boot(): void
    {
        // Publish config & migration
        $this->publishes([
            __DIR__.'/../config/weaversso.php' => config_path('weaversso.php'),
        ], 'weaversso-config');

        // $this->publishes([
        //     __DIR__ . '/../database/migrations/2025_01_01_000000_create_weaver_accounts_table.php'
        //         => database_path('migrations/2025_01_01_000000_create_weaver_accounts_table.php'),
        // ], 'weaversso-migrations');

        // Load package routes (group under auth middleware by default)
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load package resources if needed later        // Load package resources if needed later (views, translations, etc.)
    }
}

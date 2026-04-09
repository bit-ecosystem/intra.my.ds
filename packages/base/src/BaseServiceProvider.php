<?php

declare(strict_types=1);

namespace Bites\Base;

use Illuminate\Support\ServiceProvider;
use Bites\Base\Blueprint\BlockRegistry;

class BaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-blueprints.php', 'filament-blueprints');

        // Tag block services listed in config
        $blockClasses = (array) config('filament-blueprints.blocks', []);
        $this->app->tag($blockClasses, config('filament-blueprints.container_tag', 'filament.blueprints.blocks'));

        // Registry singleton resolving tagged services
        $this->app->singleton(function ($app): BlockRegistry {
            $tag = config('filament-blueprints.container_tag', 'filament.blueprints.blocks');
            /** @var iterable<BlockContract> $services */
            $services = $app->tagged($tag);

            return new BlockRegistry($services);
        });
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/filament-blueprints.php' => config_path('filament-blueprints.php'),
        ], 'filament-blueprints-config');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'workflow');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'workflow');
    }
}

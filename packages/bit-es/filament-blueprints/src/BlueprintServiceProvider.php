<?php

declare(strict_types=1);

namespace Bites\FilamentBlueprints;

use Bites\FilamentBlueprints\Contracts\BlockContract;
use Illuminate\Support\ServiceProvider;

final class BlueprintServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/filament-blueprints.php', 'filament-blueprints');

        // Tag block services listed in config
        $blockClasses = (array) config('filament-blueprints.blocks', []);
        $this->app->tag($blockClasses, config('filament-blueprints.container_tag', 'filament.blueprints.blocks'));

        // Registry singleton resolving tagged services
        $this->app->singleton(function ($app): \Bites\FilamentBlueprints\BlockRegistry {
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
            __DIR__.'/../config/filament-blueprints.php' => config_path('filament-blueprints.php'),
        ], 'filament-blueprints-config');

        // (optional) load routes/views if you ship demos
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-blueprints');
    }
}

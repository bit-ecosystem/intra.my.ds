<?php

declare(strict_types=1);

namespace Bites\CorpLogin;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;

class CorpLoginServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'corp-login');

        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('corp-login', [
                'path' => __DIR__ . '/../resources/svg',
                'prefix' => 'bites',
            ]);
        });
    }

    public function register(): void
    {
        // noop
    }
}

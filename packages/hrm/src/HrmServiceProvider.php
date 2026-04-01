<?php

declare(strict_types=1);

namespace Bites\Hrm;

use Illuminate\Support\ServiceProvider;

class HrmServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

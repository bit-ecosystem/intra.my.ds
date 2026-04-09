<?php

declare(strict_types=1);

namespace Bites\Employment;

use Illuminate\Support\ServiceProvider;

class EmploymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

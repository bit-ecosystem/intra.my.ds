<?php

namespace App\Providers;

use App\Support\LockWhenFilledMacro;
use Illuminate\Support\ServiceProvider;

class FilamentMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        LockWhenFilledMacro::register();
    }
}

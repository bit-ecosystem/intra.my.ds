<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\LockWhenFilledMacro;

class FilamentMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        LockWhenFilledMacro::register();
    }
}

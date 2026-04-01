<?php

declare(strict_types=1);

namespace Bites\Workflow;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // $this->mergeConfigFrom(__DIR__.'/../config/workflow.php', 'workflow');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'workflow');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'workflow');
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\ModelInfo\ModelFinder;

class ListModels extends Command
{
    protected $signature = 'bites:list-models';

    protected $description = 'List all Eloquent models in the application';

    public function handle(): void
    {
        $models = ModelFinder::all();
        dd($models);
        // if ($models->isEmpty()) {
        //     $this->warn('No models found.');
        //     return;
        // }

        // $this->table(['Model Class'], $models->map(fn($model) => [$model->class])->toArray());
    }
}

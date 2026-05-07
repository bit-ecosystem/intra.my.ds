<?php

declare(strict_types=1);

namespace Bites\Platform\Utility;

use Bites\Platform\Sync\Entities\ApiConfig;
use Bites\Platform\Sync\Services\FetchService;
use Illuminate\Console\Command;

class ApiFetchCommand extends Command
{
    protected $signature = 'bites:fetch {identifier? : ID or name of the config}';

    protected $description = 'Fetch data using API pipeline configs';

    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $configs = match (true) {
            is_numeric($identifier) => ApiConfig::where('id', $identifier)->get(),

            is_string($identifier) => ApiConfig::where('name', $identifier)->get(),

            default => ApiConfig::where('active', true)->get(),
        };

        if ($configs->isEmpty()) {
            $this->error('No API config found.');

            return self::FAILURE;
        }

        foreach ($configs as $config) {
            app(FetchService::class)->fetch($config);
        }

        $this->info('API fetch complete');

        return self::SUCCESS;
    }
}

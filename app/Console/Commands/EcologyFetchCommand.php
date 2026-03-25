<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Bites\Shared\Models\ApiData;
use Bites\Service\Services\EcologyFetchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EcologyFetchCommand extends Command
{
    protected $signature = 'bites:fetch-ecology
                            {template : Config key, e.g. ecology.uf_hr_cmed_e.by_jgchar}
                            {--bind=* : key=value pairs for template parameters}
                            {--json : Output JSON only}
                            {--store : Persist the fetched rows into api_data}
                            {--source= : Optional source tag for api_data (default: ecology)}';

    protected $description = 'Run an external SQL Server query template dynamically';

    public function handle(EcologyFetchService $ecologyFetchService): int
    {
        $template = $this->argument('template');

        // Parse --bind items (key=value)
        $bindings = [];
        foreach ((array) $this->option('bind') as $kv) {
            [$k, $v] = array_pad(explode('=', $kv, 2), 2, null);
            if ($k === null || $v === null) {
                $this->error(sprintf('Invalid --bind format: %s (expected key=value)', $kv));

                return self::INVALID;
            }

            $bindings[$k] = $v;
        }

        $this->info('Running template: '.$template);
        $this->line('Bindings: '.json_encode($bindings));

        try {
            $rows = $ecologyFetchService->run($template, $bindings);
        } catch (\Throwable $throwable) {
            $this->error('Error: '.$throwable->getMessage());

            return self::FAILURE;
        }

        // Output
        if ($this->option('json')) {
            $this->line(json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->info('Fetched '.count($rows).' rows.');
            $this->line(collect($rows)->take(5)->toJson(JSON_PRETTY_PRINT));
        }

        // Optional persist
        if ($this->option('store')) {
            try {
                $source = $this->option('source') ?? 'ecology';
                $apiData = ApiData::create([
                    'content' => $rows,               // Ensure content is a JSON column or castable array
                    'source' => $source ?: 'ecology',
                ]);

                Log::info('ApiData saved from ecology:fetch', [
                    'id' => $apiData->id,
                    'count' => is_array($rows) ? count($rows) : null,
                    'source' => $source,
                    'template' => $template,
                    'bindings' => $bindings,
                ]);

                $this->info('Stored api_data id='.$apiData->id);
            } catch (\Throwable $e) {
                $this->error('Persist failed: '.$e->getMessage());

                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}

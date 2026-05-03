<?php

declare(strict_types=1);

namespace App\Console\Commands;

// not used
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ScanModelsCommand extends Command
{
    protected $signature = 'bites:scan-models 
                            {path? : Directory to scan}
                            {--vendor : Also scan vendor/ directory}';

    protected $description = 'Scan PHP files for classes extending Model and output table => FQCN mapping';

    public function handle(): int
    {
        // Default scan path = app/Models
        $basePath = $this->argument('path')
            ? base_path($this->argument('path'))
            : app_path();

        $directories = [$basePath];

        // Include vendor if requested
        if ($this->option('vendor')) {
            $this->warn('⚠️ Scanning vendor directory (may be slow)...');
            $directories[] = base_path('packages');
            $directories[] = base_path('vendor');
        }

        $results = [];

        foreach ($directories as $directory) {
            $this->scanDirectory($directory, $results);
        }

        ksort($results);

        // Same format as dd(...) output in your seeder
        dump($results);

        return Command::SUCCESS;
    }

    private function scanDirectory(string $dir, array &$results): void
    {
        if (! is_dir($dir)) {
            $this->error('❌ Directory not found: '.$dir);

            return;
        }

        $rii = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );

        foreach ($rii as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $contents = file_get_contents($file->getRealPath());

            // Only consider classes extending Model
            if (! str_contains($contents, 'extends Model')) {
                continue;
            }

            // Extract namespace
            preg_match('/namespace\s+([^;]+);/', $contents, $nsMatch);
            $namespace = $nsMatch[1] ?? null;

            // Extract class
            preg_match('/class\s+(\w+)/', $contents, $classMatch);
            $className = $classMatch[1] ?? null;

            if (! $namespace || ! $className) {
                continue;
            }

            $fqcn = $namespace.'\\'.$className;

            // Instantiate to get table name
            try {
                $instance = new $fqcn;
                if (method_exists($instance, 'getTable')) {
                    $table = $instance->getTable();
                    $results[$table] = $fqcn;
                }
            } catch (\Throwable $e) {
                // Ignore anything we cannot instantiate (abstract classes etc.)
            }
        }
    }
}

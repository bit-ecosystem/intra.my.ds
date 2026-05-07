<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ScanPhpFilesCommand extends Command
{
    protected $signature = 'scan:php
                            {path? : Directory to scan (default: app)}
                            {--vendor : Also scan vendor/ directory}
                            {--s|simple : Simple output (one file per line)}';

    protected $description = 'Scan directories and list all PHP files';

    public function handle(): int
    {
        $basePath = $this->argument('path')
            ? base_path($this->argument('path'))
            : app_path();

        $directories = [$basePath];

        if ($this->option('vendor')) {
            $this->warn('⚠️ Scanning vendor directory (may be slow)...');
            $directories[] = base_path('packages');
            // $directories[] = base_path('vendor');
        }

        $results = [];

        foreach ($directories as $dir) {
            $this->scanDirectory($dir, $results);
        }

        // Stable order
        sort($results);

        // ✅ Simple mode: one line per file
        if ($this->option('simple')) {
            foreach ($results as $item) {
                $this->line($item['filename'].', '.$item['path']);
            }

            return self::SUCCESS;
        }

        // Default (structured) output
        dump($results);

        return self::SUCCESS;
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

            $results[] = [
                'filename' => $file->getFilename(),
                'path' => $file->getRealPath(),
            ];
        }
    }
}

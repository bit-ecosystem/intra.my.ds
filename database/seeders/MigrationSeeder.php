<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class MigrationSeeder extends Seeder
{
    public function run()
    {
        echo "Starting ModelJsonSeeder...\n";

        // ✅ Scan all models (app/, packages/, vendor/)
        $models = $this->scanModels([
            app_path(),
            base_path('packages'),
            base_path('vendor'),
        ]);

        // dd($models->toArray());

        // ✅ Locate JSON seeds
        $jsonFiles = File::files(base_path('database/seeders'));

        foreach ($jsonFiles as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $data = json_decode(file_get_contents($file->getRealPath()), true);

            if (!is_array($data)) {
                continue;
            }

            foreach ($data as $table => $records) {
                if (!Schema::hasTable($table)) {
                    echo "⚠️ Skipped: Table '{$table}' does not exist\n";
                    continue;
                }

                if (!isset($models[$table])) {
                    echo "⚠️ Skipped: No model found for table '{$table}'\n";
                    continue;
                }

                $modelClass = $models[$table];
                $modelClass::truncate();

                foreach ($records as $record) {
                    if (method_exists($modelClass, 'resolveCreation')) {
                        $modelClass::resolveCreation($record);
                    } else {
                        $modelClass::firstOrCreate(['id' => $record['id']], $record);
                    }
                }

                echo "✅ Seeded {$table} using {$modelClass}\n";
            }
        }
    }

    /**
     * ✅ Model scanner — same logic as ScanModelsCommand
     */
    private function scanModels(array $directories)
    {
        $results = [];

        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir)
            );

            foreach ($iterator as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $contents = file_get_contents($file->getRealPath());

                // Must contain "extends Model"
                if (!str_contains($contents, 'extends Model')) {
                    continue;
                }

                // Extract namespace
                preg_match('/namespace\s+([^;]+);/', $contents, $nsMatch);
                $namespace = $nsMatch[1] ?? null;

                // Extract class
                preg_match('/class\s+([A-Za-z0-9_]+)/', $contents, $classMatch);
                $class = $classMatch[1] ?? null;

                if (!$namespace || !$class) {
                    continue;
                }

                $fqcn = $namespace . '\\' . $class;

                // Try creating instance to extract table name
                try {
                    $instance = new $fqcn;
                    if (method_exists($instance, 'getTable')) {
                        $results[$instance->getTable()] = $fqcn;
                    }
                } catch (\Throwable $e) {
                    // Ignore non-instantiable classes
                }
            }
        }

        ksort($results);

        return collect($results);
    }
}
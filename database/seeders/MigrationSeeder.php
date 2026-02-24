<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Spatie\ModelInfo\ModelFinder;

class MigrationSeeder extends Seeder
{
    public static $manualModels = [
        'roles' => \Spatie\Permission\Models\Role::class,
        'permissions' => \Spatie\Permission\Models\Permission::class,
        'oauth_clients' => \Laravel\Passport\Client::class,
        // Add any other vendor or custom models here
    ];

    public function run()
    {
        echo "Starting MigrationSeeder...\n";
        // Collect all models and map table => class
        $autoModels = collect(ModelFinder::all())->mapWithKeys(function ($class) {
            $instance = new $class;

            return [$instance->getTable() => $class];
        });
        // dd($autoModels);
        $models = collect(self::$manualModels)->merge($autoModels);

        //  dd($models->toArray()); // Debug: see mapping

        // Get all JSON files in database/seeders
        $jsonFiles = File::files(base_path('database/seeders'));

        foreach ($jsonFiles as $file) {
            if ($file->getExtension() === 'json') {
                $data = json_decode(file_get_contents($file->getRealPath()), true);

                if (is_array($data)) {
                    foreach ($data as $table => $records) {
                        if (! Schema::hasTable($table)) {
                            echo "⚠️ Skipped: Table '{$table}' does not exist\n";

                            continue;
                        }

                        if (! isset($models[$table])) {
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
        }
    }
}

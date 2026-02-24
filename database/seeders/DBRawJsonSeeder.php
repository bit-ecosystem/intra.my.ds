<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DBRawJsonSeeder extends Seeder
{
    public function run()
    {
        // Get all JSON files in the seeders directory
        $jsonFiles = File::files(base_path('database/seeders'));

        foreach ($jsonFiles as $file) {
            if ($file->getExtension() === 'json') {
                $data = json_decode(file_get_contents($file->getRealPath()), true);

                if (is_array($data)) {
                    foreach ($data as $table => $records) {
                        if (! Schema::hasTable($table)) {
                            echo "⚠️ Skipped: Table '{$table}' does not exist (File: {$file->getFilename()})\n";

                            continue;
                        }

                        if (is_array($records)) {
                            echo "✅ Seeding table: {$table}";
                            DB::table($table)->truncate(); // Clear table before seeding
                            DB::table($table)->insert($records); // Insert as-is
                            echo "✅ Seeded table: {$table} from file: {$file->getFilename()}\n";
                        } else {
                            echo "⚠️ Skipped: Invalid data for table '{$table}' in {$file->getFilename()}\n";
                        }
                    }
                }
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BuildLangFromSource extends Command
{
    protected $signature = 'bites:build-lang 
        {--locale=en : Target locale}
        {--paths=app,resources,routes : Comma-separated paths to scan}
        {--json : Also generate JSON lang file}';

    protected $description = 'Scan PHP files for __("...") calls and generate lang files';

    public function handle(): int
    {
        $locale = $this->option('locale');
        $paths = array_map('trim', explode(',', $this->option('paths')));

        $this->info('Scanning translation keys…');

        $keys = [];

        foreach ($paths as $path) {
            $fullPath = base_path($path);

            if (! is_dir($fullPath)) {
                continue;
            }

            foreach (File::allFiles($fullPath) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $content = $file->getContents();

                preg_match_all(
                    "/__\(\s*['\"]([^'\"]+)['\"]\s*(?:,|\))/m",
                    $content,
                    $matches
                );

                foreach ($matches[1] as $key) {
                    $keys[$key] ??= $this->humanize($key);
                }
            }
        }

        if (empty($keys)) {
            $this->warn('No translation keys found.');

            return self::SUCCESS;
        }

        ksort($keys);

        $this->buildPhpLangFile($locale, $keys);

        if ($this->option('json')) {
            $this->buildJsonLangFile($locale, $keys);
        }

        $this->info('✅ Language generation completed.');

        return self::SUCCESS;
    }

    private function buildPhpLangFile(string $locale, array $keys): void
    {
        $dir = resource_path("lang/{$locale}");
        $file = "{$dir}/auto.php";

        File::ensureDirectoryExists($dir);

        $existing = File::exists($file)
            ? require $file
            : [];

        $merged = array_merge($keys, $existing);

        File::put(
            $file,
            "<?php\n\nreturn ".var_export($merged, true).";\n"
        );

        $this->info('📘 auto.php: '.count($merged).' keys');
    }

    private function buildJsonLangFile(string $locale, array $keys): void
    {
        $file = resource_path("lang/{$locale}.json");

        $existing = [];

        if (File::exists($file)) {
            $existing = json_decode(File::get($file), true) ?? [];
        }

        // Do NOT overwrite existing values
        $merged = $existing + $keys;

        File::put(
            $file,
            json_encode(
                $merged,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );

        $this->info("📙 {$locale}.json: ".count($merged).' keys');
    }

    private function humanize(string $key): string
    {
        return ucfirst(str_replace(['.', '_'], ' ', $key));
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EcologyFetchService
{
    public function run(string $key, array $bindings = []): array
    {
        $tpl = Config::get('query_templates.'.$key);
        // dd($key, $tpl,"query_templates.$key");
        if (! $tpl || empty($tpl['enabled'])) {
            throw new \RuntimeException(sprintf('Query template [%s] not found or disabled.', $key));
        }

        // Validate bindings against declared params
        $rules = [];
        foreach ($tpl['params'] ?? [] as $p) {
            $name = $p['name'];
            $required = ($p['required'] ?? false) ? 'required' : 'nullable';
            $type = match ($p['type'] ?? 'string') {
                'int' => 'integer',
                'date' => 'date',
                default => 'string',
            };
            $rules[$name] = sprintf('%s|%s', $required, $type);
        }

        Validator::make($bindings, $rules)->validate();

        // Build ordered parameters; visitdate appears twice in the SQL
        $ordered = [];
        foreach ($tpl['params'] ?? [] as $p) {
            $name = $p['name'];
            $val = $bindings[$name] ?? null;
            if ($name === 'visitdate' && str_contains($tpl['sql'], '( ? IS NULL OR validasof <= ? )')) {
                $ordered[] = $val; // IS NULL check
                $ordered[] = $val; // <= comparison
            } else {
                $ordered[] = $val;
            }
        }

        $rows = DB::connection($tpl['connection'])->select($tpl['sql'], $ordered);

        return array_map(static fn ($r): array => (array) $r, $rows);
    }
}

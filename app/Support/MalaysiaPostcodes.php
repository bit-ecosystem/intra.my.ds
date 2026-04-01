<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MalaysiaPostcodes
{
    protected const RAW_URL = 'https://raw.githubusercontent.com/atqnp/postcode-malaysia/refs/heads/master/postcode/postcode_my.csv';

    /**
     * Download & parse CSV (cached).
     *
     * Normalizes row keys to:
     * - postcode
     * - state
     * - city
     * - area
     */
    public static function rows(): array
    {
        return Cache::remember('postcode_my.csv:rows:v1', now()->addDays(7), function (): array {
            $response = Http::timeout(15)->get(self::RAW_URL);
            if (! $response->ok()) {
                return [];
            }

            $rows = [];
            $fh = fopen('php://temp', 'r+');
            fwrite($fh, $response->body());
            rewind($fh);

            $headers = [];
            if (($first = fgetcsv($fh)) !== false) {
                $headers = array_map(
                    fn ($h) => strtolower(trim(preg_replace('/\s+/', '_', (string) $h))),
                    $first
                );
            }

            while (($data = fgetcsv($fh)) !== false) {
                if (count($data) !== count($headers)) {
                    continue;
                }

                $raw = array_combine($headers, $data);

                $rows[] = [
                    'postcode' => $raw['postcode'] ?? $raw['poscode'] ?? $raw['zip'] ?? null,
                    'state' => $raw['state'] ?? $raw['negeri'] ?? $raw['state_name'] ?? null,
                    'city' => $raw['post_office'] ?? $raw['district'] ?? $raw['bandar'] ?? $raw['daerah'] ?? null,
                    'area' => $raw['location'] ?? $raw['locality'] ?? $raw['mukim'] ?? $raw['place'] ?? null,
                    '_raw' => $raw,
                ];
            }

            fclose($fh);

            return array_values(array_filter($rows, fn (array $r): bool => ! empty($r['postcode'])));
        });
    }

    /** Unique list of postcodes (strings) for datalist. */
    public static function postcodeList(): array
    {
        return array_values(
            array_unique(
                array_filter(
                    array_map(fn (array $r) => $r['postcode'] ?? null, self::rows())
                )
            )
        );
    }

    /** Unique list of formatted labels (e.g., "Shah Alam · Selangor · 40460") for city/area datalist. */
    public static function cityAreaLabels(): array
    {
        $labels = [];
        foreach (self::rows() as $r) {
            $label = trim(implode(' · ', array_filter([$r['state'] ?? null, $r['postcode'] ?? null, $r['city'] ?? null, $r['area'] ?? null])));
            if ($label !== '') {
                $labels[$label] = true;
            }
        }

        return array_keys($labels);
    }

    /** First row by exact postcode. */
    public static function byPostcode(?string $postcode): ?array
    {
        if (! $postcode) {
            return null;
        }

        foreach (self::rows() as $r) {
            if ((string) $r['postcode'] === $postcode) {
                return $r;
            }
        }

        return null;
    }

    /** First row where the "label" matches exactly a datalist choice. */
    public static function byCityAreaLabel(?string $label): ?array
    {
        if (! $label) {
            return null;
        }

        foreach (self::rows() as $r) {
            $candidate = trim(implode(' · ', array_filter([$r['city'] ?? null, $r['area'] ?? null, $r['state'] ?? null, $r['postcode'] ?? null])));
            if ($candidate === $label) {
                return $r;
            }
        }

        return null;
    }
}

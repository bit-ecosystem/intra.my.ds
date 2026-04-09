<?php

declare(strict_types=1);

namespace Bites\Service\Jobs;

use Bites\Service\Services\StaffImportService;
use Bites\Service\Models\ApiData;
use Bites\Service\Support\WeaverToStaff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SeedStaffFromApiData implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $apiDataId;

    public const ECOLOGY_SOURCE = 'MWSQLDBQ03.my.ds.amkor.com/ecology';

    public int $tries = 5;

    public array $backoff = [10, 30, 60, 120, 300];

    public function __construct(int $apiDataId)
    {
        $this->apiDataId = $apiDataId;
    }

    public function tags(): array
    {
        return ['staff', 'api-data:'.$this->apiDataId];
    }

    public function handle(): void
    {
        try {
            $apiData = ApiData::find($this->apiDataId);
            if (! $apiData || ! is_array($apiData->content)) {
                Log::warning('SeedStaffFromApiData: Missing ApiData or invalid content', [
                    'api_data_id' => $this->apiDataId,
                    'content_type' => $apiData ? gettype($apiData->content) : null,
                ]);

                return;
            }

            if (($apiData->source ?? '') !== self::ECOLOGY_SOURCE) {
                Log::info('SeedStaffFromApiData: Skipping due to source mismatch', [
                    'expected_source' => self::ECOLOGY_SOURCE,
                    'actual_source' => $apiData->source,
                    'api_data_id' => $apiData->id,
                ]);

                return;
            }

            $transformed = WeaverToStaff::transformAll($apiData->content);

            $transformed = array_values(array_filter($transformed, function ($r): bool {
                if (! is_array($r)) {
                    return false;
                }

                return trim((string) ($r['staff_number'] ?? '')) !== '';
            }));

            if ($transformed === []) {
                Log::info('SeedStaffFromApiData: No valid rows after transform/filter', [
                    'api_data_id' => $apiData->id,
                    'total_rows' => count($apiData->content),
                ]);

                return;
            }

            $meta = [
                'source' => $apiData->source,
                'timestamp' => optional($apiData->timestamp)->toIso8601String(),
                'api_data_id' => $apiData->id,
            ];

            $summary = app(StaffImportService::class)->import($transformed, $meta, chunk: 1000);

            Log::info('SeedStaffFromApiData: Import complete', [
                'api_data_id' => $apiData->id,
                'summary' => $summary,
            ]);
        } catch (Throwable $throwable) {
            Log::error('SeedStaffFromApiData: Failed', [
                'api_data_id' => $this->apiDataId,
                'summary' => $throwable,
                'message' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);
            $this->fail($throwable);
        }
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping('api-data:'.$this->apiDataId),
        ];
    }
}

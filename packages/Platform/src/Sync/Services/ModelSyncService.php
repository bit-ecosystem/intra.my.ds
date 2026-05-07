<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Services;

use Illuminate\Database\Eloquent\Model;

class ModelSyncService
{
    public function sync(
        string $modelClass,
        ?string $uniqueBy,
        bool $addExtra,
        array $row
    ): ?Model {
        /** @var Model $model */
        $model = new $modelClass;

        $fillable = array_flip($model->getFillable());

        // Split row
        $fillableRow = array_intersect_key($row, $fillable);
        $remaining = array_diff_key($row, $fillable);

        // ✅ Upsert / create
        if ($uniqueBy && isset($fillableRow[$uniqueBy])) {
            $model = $modelClass::query()->updateOrCreate(
                [$uniqueBy => $fillableRow[$uniqueBy]],
                $fillableRow
            );
        } else {
            $model = $modelClass::query()->create($fillableRow);
        }

        // ✅ Extras
        if ($addExtra && $remaining !== [] && method_exists($model, 'setExtra')) {
            foreach ($remaining as $key => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $model->setExtra($key, $value);
            }
        }

        return $model;
    }
}

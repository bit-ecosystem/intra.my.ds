<?php

declare(strict_types=1);

namespace Bites\Platform\Sync\Services;

use Bites\Platform\Sync\Entities\ApiData;

class MappingService
{
    public function run(ApiData $data): void
    {
        foreach ($data->api_config->mapping as $entity) {
            $this->processEntity($entity, $data->payload);
        }
    }

    protected function processEntity(array $entity, array $payload, $parent = null): void
    {
        $path = $entity['path'] ?? '';
        $items = $path === '' ? $payload : data_get($payload, $path);

        if (! is_array($items)) {
            return;
        }

        if (! ($entity['many'] ?? false)) {
            $items = [$items];
        }

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $row = [];
            $usedKeys = [];

            foreach ($entity['fields'] as $field) {

                // Determine value
                if (array_key_exists('value', $field)) {
                    // ✅ Static value
                    $value = $field['value'];
                } else {
                    // ✅ Value from payload
                    $value = data_get($item, $field['from'] ?? null);

                    // Track used payload keys ONLY
                    if (isset($field['from']) && is_string($field['from'])) {
                        $usedKeys[] = $field['from'];
                    }
                }

                // Optional regex
                if (isset($field['regex']) && is_string($value)) {
                    $value = preg_replace($field['regex'], '$1', $value) ?? $value;
                }

                $row[$field['to']] = $value;
            }

            if (isset($entity['skip_if'])) {
                $rule = $entity['skip_if'];

                if (
                    isset($rule['field'], $rule['min_length']) &&
                    isset($row[$rule['field']]) &&
                    mb_strlen(trim((string) $row[$rule['field']])) < $rule['min_length']
                ) {
                    continue;
                }
            }

            if ($parent && isset($entity['foreign_key'])) {
                $row[$entity['foreign_key']] = $parent->id;
            }

            $remaining = array_diff_key(
                $item,
                array_flip($usedKeys)
            );

            $model = app(ModelSyncService::class)->sync(
                modelClass: $entity['model'],
                uniqueBy: $entity['unique_by'] ?? null,
                addExtra: $entity['add_extra'] ?? false,
                row: $row
            );

            foreach ($entity['children'] ?? [] as $child) {
                $this->processEntity($child, $item, $model);
            }
        }
    }
}

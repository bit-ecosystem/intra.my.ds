<?php

declare(strict_types=1);

namespace Bites\FilamentBlueprints\Traits;

trait AppliesProps
{
    protected function applyProps(object $component, array $props, array $allowed): object
    {
        foreach ($props as $key => $value) {
            if ($value === null || ! array_key_exists($key, $allowed)) {
                continue;
            }

            $method = $allowed[$key];

            if (! method_exists($component, $method)) {
                continue;
            }

            $value = $this->coerceValue($value);
            $args = is_array($value) ? $value : [$value];
            $component = $component->{$method}(...$args);
        }

        return $component;
    }

    protected function coerceValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true' || $lower === 'false') {
                return $lower === 'true';
            }

            if (is_numeric($value)) {
                return str_contains($value, '.') ? (float) $value : (int) $value;
            }
        }

        return $value;
    }
}

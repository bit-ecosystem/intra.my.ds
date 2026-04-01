<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Forms\Components\Field;

final class LockWhenFilledMacro
{
    public static function register(): void
    {
        self::registerLockWhenFilled();
    }

    private static function registerLockWhenFilled(): void
    {
        // Prevent double registration
        if (method_exists(Field::class, 'lockWhenFilled')) {
            return;
        }

        Field::macro('lockWhenFilled', function (
            ?callable $bypass = null,
            bool $readOnly = false,
        ) {
            /** @var Field $this */
            return $this->afterStateHydrated(function (Field $component, $state) use ($bypass, $readOnly): void {
                // 1. Check if we should ignore the lock (e.g. for Admins)
                if (is_callable($bypass) && $bypass($component, $state)) {
                    return;
                }

                // 2. If the initial hydrated state is not blank, lock it
                if (! blank($state)) {
                    $readOnly
                        ? $component->readOnly()
                        : $component->disabled()->dehydrated(); // dehydrated keeps it in the save request
                }
            });
        });
    }
}

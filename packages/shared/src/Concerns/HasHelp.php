<?php

declare(strict_types=1);

namespace Bites\Shared\Concerns;

use App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction;

// use Bites\Shared\Concerns\HasHelp;

trait HasHelp
{
    protected function getHeaderActions(): array
    {
        return array_merge(
            method_exists(get_parent_class($this), 'getHeaderActions')
                ? parent::getHeaderActions()
                : [],
            [
                OpenHelpAction::make(static::class),
            ]
        );
    }
}

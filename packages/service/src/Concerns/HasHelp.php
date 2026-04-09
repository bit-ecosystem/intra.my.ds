<?php

declare(strict_types=1);

namespace Bites\Service\Concerns;

use App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction;
use Filament\Support\Facades\FilamentView;

// use \Bites\Service\Concerns\HasHelp;

trait HasHelp
{
    // protected function getHeaderActions(): array
    // {
    //     $t= array_merge(
    //         method_exists(get_parent_class($this), 'getHeaderActions')
    //             ? parent::getHeaderActions()
    //             : [],
    //         [
    //             OpenHelpAction::make(static::class),
    //         ]
    //     );
    //     dd($t);
    //     return $t;
    // }

    protected function registerModuleActionButton(): void
    {
        FilamentView::registerRenderHook(
            'panels::page.header.after',
            fn () => view('hooks.help-action-button')
        );
    }
}

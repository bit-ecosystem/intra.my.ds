<?php

use App\Providers\AppServiceProvider;
use App\Providers\DmsServiceProvider;
use App\Providers\Filament\CorePanelProvider;
use App\Providers\Filament\DmsPanelProvider;
use App\Providers\Filament\EamPanelProvider;
use App\Providers\Filament\ErpPanelProvider;
use App\Providers\Filament\HrmPanelProvider;
use App\Providers\Filament\LmsPanelProvider;
use App\Providers\Filament\MesPanelProvider;
use App\Providers\Filament\QasPanelProvider;
use App\Providers\Filament\StaffPanelProvider;
use App\Providers\FilamentMacroServiceProvider;

return [
    AppServiceProvider::class,
    DmsServiceProvider::class,
    FilamentMacroServiceProvider::class,
    CorePanelProvider::class,
    DmsPanelProvider::class,
    EamPanelProvider::class,
    ErpPanelProvider::class,
    HrmPanelProvider::class,
    LmsPanelProvider::class,
    MesPanelProvider::class,
    QasPanelProvider::class,
    StaffPanelProvider::class,
    // App\Services\RagService::class,
];

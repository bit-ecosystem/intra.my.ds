<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Activities\Pages;

use App\Filament\Erp\Resources\Workflow\Activities\ActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;
}

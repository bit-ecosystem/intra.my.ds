<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Transitions\Pages;

use App\Filament\Erp\Resources\Workflow\Transitions\TransitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransition extends CreateRecord
{
    protected static string $resource = TransitionResource::class;
}

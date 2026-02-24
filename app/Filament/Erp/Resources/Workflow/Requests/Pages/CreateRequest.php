<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Requests\Pages;

use App\Filament\Erp\Resources\Workflow\Requests\RequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;
}

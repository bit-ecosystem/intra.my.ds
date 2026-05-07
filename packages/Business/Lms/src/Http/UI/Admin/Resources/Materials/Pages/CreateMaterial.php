<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
}

<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Evaluations\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Evaluations\EvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvaluations extends ListRecords
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

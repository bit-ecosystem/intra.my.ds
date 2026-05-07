<?php

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Bites\Business\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvaluation extends ViewRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Evaluations\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Evaluations\EvaluationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEvaluation extends EditRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

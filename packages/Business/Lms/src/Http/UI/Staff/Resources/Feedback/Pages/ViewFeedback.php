<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\FeedbackResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

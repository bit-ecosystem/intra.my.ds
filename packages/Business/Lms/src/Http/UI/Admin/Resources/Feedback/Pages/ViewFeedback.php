<?php

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Pages;

use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\FeedbackResource;
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

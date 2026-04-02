<?php

namespace App\Filament\Lms\Resources\Feedback\Pages;

use App\Filament\Lms\Resources\Feedback\FeedbackResource;
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

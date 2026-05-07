<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\FeedbackResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedback extends EditRecord
{
    protected static string $resource = FeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

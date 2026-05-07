<?php

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\Pages;

use Bites\Business\Lms\Http\UI\Admin\Resources\Feedback\FeedbackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedback extends ListRecords
{
    protected static string $resource = FeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

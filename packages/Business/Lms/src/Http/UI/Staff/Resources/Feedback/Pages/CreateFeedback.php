<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\FeedbackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedback extends CreateRecord
{
    protected static string $resource = FeedbackResource::class;
}

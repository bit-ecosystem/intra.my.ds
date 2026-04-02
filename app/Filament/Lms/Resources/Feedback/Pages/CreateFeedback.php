<?php

namespace App\Filament\Lms\Resources\Feedback\Pages;

use App\Filament\Lms\Resources\Feedback\FeedbackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedback extends CreateRecord
{
    protected static string $resource = FeedbackResource::class;
}

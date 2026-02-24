<?php

declare(strict_types=1);

namespace App\Filament\Dms\Pages;

use App\Services\RagService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class RagQueryPage extends Page
{
    protected string $view = 'filament.dms.pages.rag-query';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-rag';

    protected static ?string $title = 'Ask Documents (RAG)';

    public $question;

    public $answer;

    public $sources = [];

    public function submit(): void
    {
        $ragService = app(RagService::class);
        [$this->answer, $this->sources] = $ragService->query($this->question);

        Notification::make()
            ->title('Answer Generated')
            ->success()
            ->send();
    }
}

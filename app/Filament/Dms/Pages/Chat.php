<?php

declare(strict_types=1);

namespace App\Filament\Dms\Pages;

use App\Services\OllamaService;
use BackedEnum;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Reactive;

class Chat extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.dms.pages.chat';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public string $message = '';

    public array $messages = [];

    #[Reactive]
    public string $stream = '';

    public string $selectedModel = 'qwen2.5-coder:3b';

    public string $chatText = ''; // ✅ This will be the chat box textarea

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    protected function getFormSchema(): array
    {
        return [
            // Textarea::make('chatText')
            //     ->label('Conversation')
            //     ->rows(12)
            //     ->html()
            //     ->reactive()
            //     ->disabled(), // Read-only
            // TextEntry::make('chatText')
            //     ->html(),
            MarkdownEditor::make('chatText')
                ->reactive()
                ->maxHeight('100px')
                ->disabled(), // read-only for bot response

            ToggleButtons::make('selectedModel')
                ->label('Ask')
                ->options(fn () => collect(app(OllamaService::class)->listModels())->mapWithKeys(fn ($m): array => [$m => $m])->toArray())
                ->inline()
                ->grouped()
                ->required(),
            Textarea::make('message')
                ->label('Your Message')
                ->rows(3)
                ->required(),
        ];
    }

    public function sendMessage(OllamaService $ollamaService): void
    {
        if (trim($this->message) === '' || trim($this->message) === '0') {
            return;
        }

        // Add user message to chat history
        $userMessage = [
            'role' => 'Aku',
            'content' => $this->message,
        ];
        $this->messages[] = $userMessage;

        $prompt = $this->message;
        $this->message = '';

        // ✅ Reset chatText and include user message first

        $this->chatText .= ($this->chatText === '' || $this->chatText === '0' ? '<span class="text-primary-600">You : '.$prompt.'</span>' : '<br><br><span class="text-primary-600">You : '.$prompt.'</span>');
        //  $this->chatText .= '<br><span style="color:black;">You : ' . $prompt . '</span>';

        // Add assistant placeholder
        $assistantIndex = count($this->messages);
        $this->messages[] = [
            'role' => 'assistant',
            'content' => '',
        ];
        $this->chatText .= '<br>Bot : ';
        // ✅ Stream chunks
        $ollamaService->streamChat($prompt, $this->selectedModel, function (string $chunk) use ($assistantIndex): void {
            // Stream to Livewire
            $this->stream(content: $chunk, to: 'stream');

            // Append chunk to messages
            $this->messages[$assistantIndex]['content'] .= $chunk;

            // ✅ Append only the new chunk to chatText
            $this->chatText .= $chunk;
        });
    }
}

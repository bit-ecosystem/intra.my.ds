<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Actions;

use Bites\Service\Models\HelpPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class OpenHelpAction
{
    public static function make(
        ?string $pageClass = null,
        ?Model $record = null,
    ): Action {

        $resolvedPage = ltrim($pageClass ?? self::class, '\\');
        $pageKey = Str::replace('\\', '.', $resolvedPage);
        $recordId = $record?->getKey();

        $existing = HelpPage::query()
            ->where('page_class', $pageKey)
            ->where('record', $recordId)
            ->first();

        if (blank($existing?->content)) {
            if (Auth::user()->can('create_Core_HelpPage')) {
                return self::createMode($pageKey, $resolvedPage, $recordId);
            }

            return self::genericMode($resolvedPage, $recordId);
        }

        return self::viewMode($existing);
    }

    private static function createMode(
        string $pageKey,
        string $resolvedPage,
        ?int $recordId,
    ): Action {

        $template = self::defaultTemplate($resolvedPage, $recordId);

        return Action::make('help')
            ->label(__('Help'))
            ->icon('heroicon-m-question-mark-circle')
            ->slideOver()
            ->outlined()
            ->color('gray')
            ->modalHeading(__('Create Help Content'))
            ->modalSubmitActionLabel(__('Save'))
            ->modalCancelActionLabel(__('Close'))
            ->schema([
                Hidden::make('page_class')->default($pageKey),
                Hidden::make('record')->default($recordId),

                MarkdownEditor::make('content'),
                // RichEditor::make('content')
                //     ->label(__('Help Content'))
                //     ->default($template)
                //     ->required()
                //     ->columnSpanFull(),
            ])
            ->action(function (array $data): void {
                HelpPage::updateOrCreate(
                    [
                        'page_class' => $data['page_class'],
                        'record' => $data['record'],
                    ],
                    [
                        'content' => $data['content'],
                        'panel_id' => 'core',
                        'org_unit_id' => null,
                        'title' => 'Help for '.
                            Str::headline(class_basename($data['page_class'])),
                    ],
                );

                Notification::make()
                    ->title(__('Help content created'))
                    ->success()
                    ->send();
            });
    }

    private static function viewMode(HelpPage $existing): Action
    {
        return Action::make('help')
            ->label(__('Help'))
            ->icon('heroicon-m-question-mark-circle')
            ->slideOver()
            ->outlined()
            ->modalHeading(__('Help'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('Close'))
            ->schema([
                TextEntry::make('content')
                    ->state($existing->content)
                    // ->html()
                    ->markdown()
                    ->columnSpanFull(),
            ]);
    }

    private static function defaultTemplate(
        string $pageClass,
        ?int $recordId,
    ): string {

        $title = Str::headline(class_basename($pageClass));

        return <<<HTML
<h2><strong>{$title} Help {$recordId}</strong></h2>
 
<p>Start by describing what this page is for and who should use it.</p>
 
<h3><u>Overview</u></h3>
<p>
- Purpose:<br>
- Audience:<br>
- Related resources:
</p>
 
<h3><u>Common Actions</u></h3>
<p>
1. …<br>
2. …<br>
3. …
</p>
 
<h3><u>Tips</u></h3>
<p>
- …<br>
- …
</p>
 
<h3><u>FAQ</u></h3>
<p>
<strong>Q:</strong> …<br>
<strong>A:</strong> …
</p>
HTML;
    }

    private static function genericMode(string $pageClass, ?int $recordId): Action
    {
        $template = self::defaultTemplate($pageClass, $recordId);

        return Action::make('help')
            ->label(__('Help'))
            ->icon('heroicon-m-question-mark-circle')
            ->slideOver()
            ->modalHeading(__('Help'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('Close'))
            ->schema([
                TextEntry::make('content')
                    ->state(
                        // Localize this string as you prefer
                        '<p><em>'.__('No help content has been written for this page yet.').'</em></p>'.
                            $template
                    )
                    ->html()
                    ->columnSpanFull(),
            ]);
    }
}

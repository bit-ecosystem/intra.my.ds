<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Actions;

use Bites\Shared\Models\HelpPage;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class MakeHelp
{
    public static function make(): Action
    {
        return Action::make('help')
            ->label(__('Help'))
            ->icon('heroicon-m-question-mark-circle')
            ->slideOver()
            ->mountUsing(function (Action $action, $livewire): void {

                $pageClass = get_class($livewire);
                $resolvedPage = ltrim($pageClass, '\\');
                $pageKey = str_replace('\\', '.', $resolvedPage);

                $record = property_exists($livewire, 'record')
                    ? $livewire->record
                    : null;

                $meta = self::extractRecordMeta($record);

                $existing = HelpPage::query()
                    ->where('page_class', $pageKey)
                    ->where('record', $meta['record_id'])
                    ->first();

                if (filled($existing?->content)) {
                    self::viewMode($action, $existing->content);

                    return;
                }

                if (self::canCreateHelp($meta['org_unit_id'])) {
                    self::createMode($action, $pageKey, $resolvedPage, $meta);

                    return;
                }

                self::genericMode($action);
            });
    }

    /* -------------------------------------------------- */
    /*  MODES */
    /* -------------------------------------------------- */

    private static function viewMode(Action $action, string $content): void
    {
        $action
            ->modalHeading(__('Help'))
            ->modalSubmitAction(false)
            ->infolist([
                TextEntry::make('content')
                    ->state($content)
                    ->html()
                    ->columnSpanFull(),
            ]);
    }

    private static function genericMode(Action $action): void
    {
        $action
            ->modalHeading(__('Help'))
            ->modalSubmitAction(false)
            ->schema([
                TextEntry::make('content')
                    ->state('<p>No help or manual for this page.</p>')
                    ->html()
                    ->columnSpanFull(),
            ]);
    }

    private static function createMode(
        Action $action,
        string $pageKey,
        string $resolvedPage,
        array $meta,
    ): void {

        $action
            ->modalHeading(__('Create Help Content'))
            ->schema([
                Hidden::make('page_class')->default($pageKey),
                Hidden::make('record')->default($meta['record_id']),
                Hidden::make('record_label')->default($meta['record_label']),
                Hidden::make('org_unit_id')->default($meta['org_unit_id']),

                RichEditor::make('content')
                    ->label(__('Help Content'))
                    ->default(
                        self::defaultTemplate(
                            $resolvedPage,
                            $meta['record_label'],
                        )
                    )
                    ->required()
                    ->columnSpanFull(),
            ])
            ->action(function (array $data): void {

                HelpPage::updateOrCreate(
                    [
                        'page_class' => $data['page_class'],
                        'record' => $data['record'],
                    ],
                    [
                        'title' => 'Help for '.
                            Str::headline(
                                class_basename($data['page_class'])
                            ),
                        'content' => $data['content'],
                        'panel_id' => filament()->getCurrentPanel()->getId(),
                        'record_label' => $data['record_label'],
                        'org_unit_id' => $data['org_unit_id'],
                    ],
                );

                Notification::make()
                    ->title(__('Help content saved'))
                    ->success()
                    ->send();
            });
    }

    /* -------------------------------------------------- */
    /*  AUTHORIZATION */
    /* -------------------------------------------------- */

    private static function canCreateHelp(?int $recordOrgUnitId): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Super builder can always create
        if ($user->hasRole('hr_trainingofficer')) {
            return true;
        }

        // Org builder must match org unit
        return $user->hasRole('ou_trainer') &&
        $recordOrgUnitId &&
        $user->org_unit_id === $recordOrgUnitId;
    }

    /* -------------------------------------------------- */
    /*  HELPERS */
    /* -------------------------------------------------- */

    private static function extractRecordMeta(?Model $record): array
    {
        if (! $record instanceof Model) {
            return [
                'record_id' => null,
                'record_label' => null,
                'org_unit_id' => null,
            ];
        }

        $recordId = $record->getKey();

        $recordLabel = null;

        foreach (['title', 'name', 'code', 'number'] as $field) {
            if (filled($record->getAttribute($field))) {
                $recordLabel = (string) $record->getAttribute($field);
                break;
            }
        }

        $recordLabel ??= class_basename($record).' #'.$recordId;

        $orgUnitId = $record->getAttribute('org_unit_id') ?? null;

        return [
            'record_id' => $recordId,
            'record_label' => $recordLabel,
            'org_unit_id' => $orgUnitId,
        ];
    }

    private static function defaultTemplate(
        string $pageClass,
        ?string $recordLabel,
    ): string {

        $title = Str::headline(class_basename($pageClass));

        $recordSection = $recordLabel
            ? sprintf('<p><strong>Record:</strong> %s</p>', $recordLabel)
            : '';

        return <<<HTML
<h2><strong>{$title} Help</strong></h2>
{$recordSection}
 
<p>Describe what this page is for and who should use it.</p>
 
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
}

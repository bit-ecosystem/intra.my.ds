<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Schemas;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\HeroBlock;
use Filament\Facades\Filament;
use Filament\Forms\Components;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class HelpPageForm
{
    public static function configure(Schema $schema): Schema
    {

        $panelOptions = collect(Filament::getPanels())
            ->mapWithKeys(fn ($panel): array => [$panel->getId() => Str::headline($panel->getId())])
            ->all();

        return $schema
            ->components([
                // Grid::make()
                //     ->schema([
                Components\Select::make('panel_id')
                    ->label('Panel')
                    ->options($panelOptions)
                    ->required()
                    ->native(false)
                    ->helperText('Which Filament panel this help page applies to.'),

                Components\TextInput::make('resource_class')
                    ->label('Resource Class')
                    ->placeholder('App\\Filament\\Resources\\TaskResource')
                    ->required()
                    ->helperText('Fully qualified resource class (e.g., App\\Filament\\Resources\\TaskResource).'),

                Components\TextInput::make('page_class')
                    ->label('Page Class')
                    ->placeholder('App\\Filament\\Resources\\TaskResource\\Pages\\ListTasks')
                    ->required()
                    ->helperText('Fully qualified page class for ListRecords (e.g., ...\\Pages\\ListTasks).'),
                // ])
                // ->columns(3),

                Grid::make()
                    ->schema([
                        Components\TextInput::make('slug')
                            ->label('Help Slug')
                            ->required()
                            ->unique(ignorable: fn ($record) => $record)
                            ->placeholder('tasks')
                            ->helperText('Used in route /admin/help/{slug}. Keep it URL-friendly.')
                            ->rule('alpha_dash'),

                        Components\TextInput::make('title')
                            ->label('Title')
                            ->placeholder('Managing Tasks')
                            ->maxLength(200),

                        Components\TextInput::make('external_url')
                            ->label('External URL')
                            ->placeholder('https://intranet/docs/tasks')
                            ->helperText('Optional: If set, the icon will redirect here instead of the internal help page.')
                            ->visible(false), // Add later if you want hybrid behavior
                    ])
                    ->columns(3),
                Components\MarkdownEditor::make('content')
                    ->toolbarButtons([
                        ['bold', 'italic', 'strike', 'link'],
                        ['heading'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table', 'attachFiles'],
                        ['undo', 'redo'],
                    ])
                    ->columnSpanFull(),
                // Components\RichEditor::make('content') // or rename the column to 'content'
                //     ->toolbarButtons([
                //         ['textColor', 'bold', 'italic', 'underline', 'strike', 'link'],
                //         ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                //         ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                //         ['table', 'attachFiles'],
                //         ['undo', 'redo'],
                //     ])
                //     ->floatingToolbars([
                //         'paragraph' => [
                //             'bold',
                //             'italic',
                //             'underline',
                //             'strike',
                //             'subscript',
                //             'superscript',
                //         ],
                //         'heading' => [
                //             'h1',
                //             'h2',
                //             'h3',
                //         ],
                //         'table' => [
                //             'tableAddColumnBefore',
                //             'tableAddColumnAfter',
                //             'tableDeleteColumn',
                //             'tableAddRowBefore',
                //             'tableAddRowAfter',
                //             'tableDeleteRow',
                //             'tableMergeCells',
                //             'tableSplitCell',
                //             'tableToggleHeaderRow',
                //             'tableToggleHeaderCell',
                //             'tableDelete',
                //         ],
                //     ])
                //     ->customBlocks([
                //         HeroBlock::class,
                //         // CallToActionBlock::class,
                //     ])
                //     ->customTextColors()
                //     ->columnSpanFull(),

                // RichContentRenderer::make($record->markdown)
                //     // ->customBlocks([
                //     //     HeroBlock::class,
                //     //     CallToActionBlock::class,
                //     // ])
                //     ->toHtml()
            ]);
    }
}

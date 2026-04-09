<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Resources\Blueprints\Schemas;

use Bites\Base\Blueprint\BlockRegistry;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
// v5 Schemas (layouts)
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Wizard;
// Infolists & Actions (for preview blocks)
use Filament\Schemas\Schema;

class BlueprintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema(self::getFormSchema());
    }

    /** Keep top-level clean */
    protected static function getFormSchema(): array
    {
        return [
            Section::make('Blueprint')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),

            Section::make('Form Schema')->collapsible()->schema([
                self::formBuilder(),
            ])->columnSpan('full'),

            // Section::make('Infolist Schema')->collapsible()->schema([
            //     self::infolistBuilder(),
            // ]),

            // Section::make('Actions')->collapsible()->schema([
            //     self::actionBuilder(),
            // ]),
        ];
    }

    /** --------------------------
     *  FORM BLOCKS (palette)
     *  -------------------------*/
    // protected static function formBuilder(): Builder
    // {
    //     return Builder::make('form_blocks')
    //         ->label('Form Blocks')
    //         ->blocks(self::formBlocks());
    // }

    protected static function formBuilder(): Builder
    {
        /** @var BlockRegistry $blockRegistry */
        $blockRegistry = app(BlockRegistry::class);

        return Builder::make('form_blocks')
            ->label('Form Blocks')
            ->blocks($blockRegistry->paletteBlocks());
    }

    protected static function formBlocks(): array
    {
        return [
            // Layout: Section
            Block::make('section')
                ->label('Section')
                ->schema([
                    TextInput::make('label')->required(),
                    KeyValue::make('props')->label('Section props (method => value)'),
                    Builder::make('children')->label('Children')->blocks(self::formFieldBlocks()),
                ]),

            // Layout: Grid
            Block::make('grid')
                ->label('Grid')
                ->schema([
                    KeyValue::make('columns')->label('Columns (breakpoint => count)'),
                    KeyValue::make('props')->label('Grid props'),
                    Builder::make('children')->label('Children')->blocks(self::formFieldBlocks()),
                ]),

            // Layout: Tabs
            Block::make('tabs')
                ->label('Tabs')
                ->schema([
                    KeyValue::make('props')->label('Tabs props'),
                    Builder::make('tabs')
                        ->label('Tab list')
                        ->blocks([
                            Block::make('tab')
                                ->label('Tab')
                                ->schema([
                                    TextInput::make('label')->required(),
                                    KeyValue::make('props')->label('Tab props'),
                                    Builder::make('children')->label('Children')->blocks(self::formFieldBlocks()),
                                ]),
                        ]),
                ]),

            // Layout: Wizard
            Block::make('wizard')
                ->label('Wizard')
                ->schema([
                    KeyValue::make('props')->label('Wizard props'),
                    Builder::make('steps')
                        ->label('Steps')
                        ->blocks([
                            Block::make('step')
                                ->label('Step')
                                ->schema([
                                    TextInput::make('label')->required(),
                                    KeyValue::make('props')->label('Step props'),
                                    Builder::make('children')->label('Children')->blocks(self::formFieldBlocks()),
                                ]),
                        ]),
                ]),

            // Direct field (top-level field without wrapping)
            ...self::formFieldBlocks(),
        ];
    }

    /** Fields palette for forms */
    protected static function formFieldBlocks(): array
    {
        return [
            Block::make('textInput')->label('TextInput')->columns(2)->schema([
                TextInput::make('name')->required()->placeholder('field_name'),
                TextInput::make('label')->default(''),
                KeyValue::make('sbehavior')->label('State & behavior')
                    // Pre-seed the keys you want to appear
                    ->default([
                        'label' => null,
                        'required' => null,
                        'default' => null,
                        'live' => null,
                        'reactive' => null,
                        'dehydrated' => null,
                        'dehydratedWhenEmpty' => null,
                        'afterStateUpdated' => null,

                    ])->valuePlaceholder('Enter value or leave empty for default')
                    // Make the keys non-editable and prevent extra rows
                    ->addable(false)
                    ->deletable(false)
                    ->editableKeys(false)
                    // (optional) nicer labels/placeholders
                    ->keyLabel('State & behavior')
                    ->valueLabel('Value'),

                KeyValue::make('visibility')->label('Visibility & interactivity')
                    // Pre-seed the keys you want to appear
                    ->default([
                        'disabled' => null,
                        'readOnly' => null,
                        'visible' => null,
                        'hidden' => null,
                        'helperText' => null,
                        'hint' => null,
                        'hintIcon' => null,

                    ])->valuePlaceholder('Enter value or leave empty for default')
                    // Make the keys non-editable and prevent extra rows
                    ->addable(false)
                    ->deletable(false)
                    ->editableKeys(false)
                    // (optional) nicer labels/placeholders
                    ->keyLabel('Visibility & interactivity')
                    ->valueLabel('Value'),
            ]),

            Block::make('select')->label('Select')->schema([
                TextInput::make('name')->required(),
                TextInput::make('label')->default(''),
                KeyValue::make('options')->label('Options (value => label)'),
                KeyValue::make('props')->label('Extra props'),
            ]),

            Block::make('textarea')->label('Textarea')->schema([
                TextInput::make('name')->required(),
                TextInput::make('label')->default(''),
                KeyValue::make('props')->label('Extra props'),
            ]),

            Block::make('repeater')->label('Repeater')->schema([
                TextInput::make('name')->required()->placeholder('items'),
                KeyValue::make('props')->label('Repeater props'),
                Builder::make('children')->label('Fields')->blocks([
                    Block::make('textInput')->label('TextInput')->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('label'),
                        KeyValue::make('props')->label('Props'),
                    ]),
                    Block::make('select')->label('Select')->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('label'),
                        KeyValue::make('options')->label('Options'),
                        KeyValue::make('props')->label('Props'),
                    ]),
                ]),
            ]),
        ];
    }

    /** --------------------------
     *  INFOLIST BLOCKS
     *  -------------------------*/
    protected static function infolistBuilder(): Builder
    {
        return Builder::make('infolist_blocks')
            ->label('Infolist Blocks')
            ->blocks(self::infolistBlocks());
    }

    protected static function infolistBlocks(): array
    {
        return [
            Block::make('section')->label('Section')->schema([
                TextInput::make('label')->required(),
                KeyValue::make('props')->label('Section props'),
                Builder::make('children')->blocks([
                    Block::make('textEntry')->label('TextEntry')->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('label')->default(''),
                        KeyValue::make('props')->label('Props'),
                    ]),
                ]),
            ]),

            Block::make('textEntry')->label('TextEntry')->schema([
                TextInput::make('name')->required(),
                TextInput::make('label')->default(''),
                KeyValue::make('props')->label('Props'),
            ]),
        ];
    }

    /** --------------------------
     *  ACTION BLOCKS
     *  -------------------------*/
    protected static function actionBuilder(): Builder
    {
        return Builder::make('action_blocks')
            ->label('Actions')
            ->blocks(self::actionBlocks());
    }

    protected static function actionBlocks(): array
    {
        return [
            Block::make('action')->label('Action')->schema([
                TextInput::make('name')->required()->placeholder('save'),
                TextInput::make('label')->default('Save'),
                KeyValue::make('props')->label('Props (e.g. color => primary)'),
            ]),

            Block::make('editAction')->label('EditAction')->schema([
                TextInput::make('label')->default('Edit'),
                KeyValue::make('props')->label('Props'),
                Builder::make('schema')->label('Edit modal fields')->blocks(self::formFieldBlocks()),
            ]),

            Block::make('createAction')->label('CreateAction')->schema([
                TextInput::make('label')->default('Create'),
                KeyValue::make('props')->label('Props'),
                Builder::make('schema')->label('Create modal fields')->blocks(self::formFieldBlocks()),
            ]),
        ];
    }
}

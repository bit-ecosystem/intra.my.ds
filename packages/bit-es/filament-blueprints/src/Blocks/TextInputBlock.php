<?php

namespace Bites\FilamentBlueprints\Blocks;


use Bites\FilamentBlueprints\BlockRegistry;
use Bites\FilamentBlueprints\Contracts\BlockContract;
use Bites\FilamentBlueprints\Traits\AppliesProps;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;

final class TextInputBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'textInput';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
            ->label('TextInput')
            ->columns(2)
            ->schema([
                TextInput::make('name')->required()->placeholder('field_name'),
                TextInput::make('label')->default(''),
                KeyValue::make('sbehavior')
                    ->label('State & behavior')
                    ->default([
                        'label' => null,
                        'required' => null,
                        'default' => null,
                        'live' => null,
                        'reactive' => null,
                        'dehydrated' => null,
                        'dehydratedWhenEmpty' => null,
                        'afterStateUpdated' => null,
                        'placeholder' => null,
                        'prefix' => null,
                        'suffix' => null,
                        'helperText' => null,
                        'hint' => null,
                    ])
                    ->addable(false)->deletable(false)->editableKeys(false)
                    ->valuePlaceholder('Enter value or leave empty for default'),
                KeyValue::make('visibility')
                    ->label('Visibility & interactivity')
                    ->default([
                        'disabled' => null,
                        'readOnly' => null,
                        'visible' => null,
                        'hidden' => null,
                    ])
                    ->addable(false)->deletable(false)->editableKeys(false)
                    ->valuePlaceholder('Enter value or leave empty for default'),
            ]);
    }

    public function decode(array $payload, \Bites\FilamentBlueprints\BlockRegistry $registry): TextInput
    {
        $name        = $payload['name']   ?? 'text_input';
        $label       = $payload['label']  ?? null;
        $sbehavior   = (array) ($payload['sbehavior'] ?? []);
        $visibility  = (array) ($payload['visibility'] ?? []);

        $component = TextInput::make($name);

        if (! empty($label)) {
            $component->label($label);
        }

        $allowedBehavior = [
            'label' => 'label',
            'required' => 'required',
            'default' => 'default',
            'live' => 'live',
            'reactive' => 'reactive',
            'dehydrated' => 'dehydrated',
            'dehydratedWhenEmpty' => 'dehydratedWhenEmpty',
            'afterStateUpdated' => 'afterStateUpdated',
            'placeholder' => 'placeholder',
            'prefix' => 'prefix',
            'suffix' => 'suffix',
            'helperText' => 'helperText',
            'hint' => 'hint',
        ];

        $allowedVisibility = [
            'disabled' => 'disabled',
            'readOnly' => 'readOnly',
            'visible' => 'visible',
            'hidden' => 'hidden',
        ];

        $component = $this->applyProps($component, array_filter($sbehavior, fn($v) => $v !== null), $allowedBehavior);
        $component = $this->applyProps($component, array_filter($visibility, fn($v) => $v !== null), $allowedVisibility);

        return $component;
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}
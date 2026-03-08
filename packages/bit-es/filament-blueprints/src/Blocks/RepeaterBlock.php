<?php

namespace Bites\FilamentBlueprints\Blocks;


use Bites\FilamentBlueprints\BlockRegistry;
use Bites\FilamentBlueprints\Contracts\BlockContract;
use Bites\FilamentBlueprints\Traits\AppliesProps;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

final class RepeaterBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'select';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
            ->label('Select')
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('label')->default(''),
                KeyValue::make('options')->label('Options (value => label)'),
                KeyValue::make('props')->label('Extra props'),
            ]);
    }

   public function decode(array $payload, \Bites\FilamentBlueprints\BlockRegistry $registry): TextInput
     {
        $name    = $payload['name'] ?? 'select';
        $label   = $payload['label'] ?? null;
        $options = (array) ($payload['options'] ?? []);
        $props   = (array) ($payload['props'] ?? []);

        $component = Repeater::make($name)->options($options);
        if (! empty($label)) {
            $component->label($label);
        }

        $allowed = [
            'searchable' => 'searchable',
            'multiple' => 'multiple',
            'native' => 'native',
            'placeholder' => 'placeholder',
            'helperText' => 'helperText',
            'required' => 'required',
            'reactive' => 'reactive',
            'live' => 'live',
            'disabled' => 'disabled',
            'visible' => 'visible',
            'hidden' => 'hidden',
        ];

        return $this->applyProps($component, $props, $allowed);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

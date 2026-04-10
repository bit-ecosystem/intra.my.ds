<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Blocks;

use Bites\Base\Blueprint\BlockRegistry;
use Bites\Base\Blueprint\Contracts\BlockContract;
use Bites\Base\Blueprint\Concerns\AppliesProps;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class TextareaBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'textarea';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
            ->label('Textarea')
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('label')->default(''),
                KeyValue::make('options')->label('Options (value => label)'),
                KeyValue::make('props')->label('Extra props'),
            ]);
    }

    public function decode(array $payload, BlockRegistry $registry): TextInput
    {
        $name = $payload['name'] ?? 'textarea';
        $label = $payload['label'] ?? null;
        $options = (array) ($payload['options'] ?? []);
        $props = (array) ($payload['props'] ?? []);

        $component = Textarea::make($name)->options($options);
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

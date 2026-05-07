<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Blocks;

use Bites\Base\Blueprint\BlockRegistry;
use Bites\Base\Blueprint\Concerns\AppliesProps;
use Bites\Base\Blueprint\Contracts\BlockContract;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select as SelectField;
use Filament\Forms\Components\TextInput;

final class SelectBlock implements BlockContract
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

    public function decode(array $payload, BlockRegistry $registry): TextInput
    {
        $name = $payload['name'] ?? 'select';
        $label = $payload['label'] ?? null;
        $options = (array) ($payload['options'] ?? []);
        $props = (array) ($payload['props'] ?? []);

        $select = SelectField::make($name)->options($options);
        if (! empty($label)) {
            $select->label($label);
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

        return $this->applyProps($select, $props, $allowed);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

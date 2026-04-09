<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Blocks;

use Bites\Base\Blueprint\BlockRegistry;
use Bites\Base\Blueprint\Contracts\BlockContract;
use Bites\Base\Blueprint\Traits\AppliesProps;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

final class WizardBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'wizard';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
            ->label('Wizard')
            ->schema([
                KeyValue::make('props')->label('Wizard props'),
                Builder::make('steps')->label('Steps')->blocks([
                    Block::make('step')
                        ->label('Step')
                        ->schema([
                            TextInput::make('label')->required(),
                            KeyValue::make('props')->label('Step props'),
                            Builder::make('children')->label('Children'),
                        ]),
                ]),
            ]);
    }

    public function decode(array $payload, BlockRegistry $registry): Wizard
    {
        $props = (array) ($payload['props'] ?? []);
        $stepsState = (array) ($payload['steps'] ?? []);

        $steps = [];

        foreach ($stepsState as $stepState) {
            $data = (array) ($stepState['data'] ?? $stepState);
            $label = $data['label'] ?? 'Step';
            $children = $registry->decodeBuilder($data['children'] ?? []);

            $step = Step::make($label)->schema($children);

            $step = $this->applyProps($step, (array) ($data['props'] ?? []), [
                'icon' => 'icon',
                'visible' => 'visible',
                'hidden' => 'hidden',
            ]);

            $steps[] = $step;
        }

        $wizard = Wizard::make($steps);

        return $this->applyProps($wizard, $props, [
            'skippable' => 'skippable',
            'contained' => 'contained',
            'columnSpan' => 'columnSpan',
        ]);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

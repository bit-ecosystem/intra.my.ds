<?php

namespace Bites\FilamentBlueprints\Blocks;

use Bites\FilamentBlueprints\BlockRegistry;
use Bites\FilamentBlueprints\Contracts\BlockContract;
use Bites\FilamentBlueprints\Traits\AppliesProps;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

final class TabsBlock implements BlockContract
{
    use AppliesProps;

    public static function name(): string
    {
        return 'tabs';
    }

    public function palette(): Block
    {
        return Block::make(self::name())
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
                                Builder::make('children')->label('Children'),
                            ]),
                    ]),
            ]);
    }

    public function decode(array $payload, BlockRegistry $registry): Tabs
    {
        $props = (array) ($payload['props'] ?? []);
        $tabsState = (array) ($payload['tabs'] ?? []);

        $tabs = [];

        foreach ($tabsState as $item) {
            $data = (array) ($item['data'] ?? $item); // support raw or Builder-shape
            $label = $data['label'] ?? 'Tab';
            $children = $registry->decodeBuilder($data['children'] ?? []);

            $tab = Tab::make($label)->schema($children);

            $tab = $this->applyProps($tab, (array) ($data['props'] ?? []), [
                'icon' => 'icon',
                'badge' => 'badge',
                'visible' => 'visible',
                'hidden' => 'hidden',
            ]);

            $tabs[] = $tab;
        }

        $component = Tabs::make()
            ->tabs($tabs);

        return $this->applyProps($component, $props, [
            'columnSpan' => 'columnSpan',
            'contained' => 'contained',
            'visible' => 'visible',
            'hidden' => 'hidden',
        ]);
    }

    public function encode(array $payload): array
    {
        return $payload;
    }
}

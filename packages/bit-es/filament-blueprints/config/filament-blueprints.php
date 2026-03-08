<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Registered blocks
    |--------------------------------------------------------------------------
    | You can add your own block class strings here from your app or other packages.
    */
    'blocks' => [
        Bites\FilamentBlueprints\Blocks\SectionBlock::class,
        Bites\FilamentBlueprints\Blocks\GridBlock::class,
        Bites\FilamentBlueprints\Blocks\TabsBlock::class,
        Bites\FilamentBlueprints\Blocks\WizardBlock::class,
        Bites\FilamentBlueprints\Blocks\TextInputBlock::class,
        Bites\FilamentBlueprints\Blocks\SelectBlock::class,
        Bites\FilamentBlueprints\Blocks\TextareaBlock::class,
        Bites\FilamentBlueprints\Blocks\RepeaterBlock::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tag name used in the container
    |--------------------------------------------------------------------------
    */
    'container_tag' => 'filament.blueprints.blocks',
];
<?php

use Bites\FilamentBlueprints\Blocks\GridBlock;
use Bites\FilamentBlueprints\Blocks\RepeaterBlock;
use Bites\FilamentBlueprints\Blocks\SectionBlock;
use Bites\FilamentBlueprints\Blocks\SelectBlock;
use Bites\FilamentBlueprints\Blocks\TabsBlock;
use Bites\FilamentBlueprints\Blocks\TextareaBlock;
use Bites\FilamentBlueprints\Blocks\TextInputBlock;
use Bites\FilamentBlueprints\Blocks\WizardBlock;

return [
    /*
    |--------------------------------------------------------------------------
    | Registered blocks
    |--------------------------------------------------------------------------
    | You can add your own block class strings here from your app or other packages.
    */
    'blocks' => [
        SectionBlock::class,
        GridBlock::class,
        TabsBlock::class,
        WizardBlock::class,
        TextInputBlock::class,
        SelectBlock::class,
        TextareaBlock::class,
        RepeaterBlock::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tag name used in the container
    |--------------------------------------------------------------------------
    */
    'container_tag' => 'filament.blueprints.blocks',
];

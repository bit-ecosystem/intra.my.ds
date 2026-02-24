<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Schemas;

use App\Services\FormFormatBuilder;
use Filament\Forms\Components;
use Filament\Schemas\Schema;

class MethodologyForm
{
    public static function configure(Schema $schema): Schema
    {
        //    $format = json_decode(
        //         file_get_contents(base_path('schemas/customer.json')),
        //         true
        //     );

        // /** @var Component[] $components */
        // $components = app(FormFormatBuilder::class)->build($format);

        // // Support both common patterns:
        // if (method_exists($schema, 'schema')) {
        //     $schema->schema($components);
        // } elseif (method_exists($schema, 'components')) {
        //     $schema->components($components);
        // } else {
        //     // If neither exists, throw a helpful error so it's easy to fix.
        //     throw new \RuntimeException('Unsupported Schema adapter: expected ->schema() or ->components().');
        // }

        return $schema;
    }
}

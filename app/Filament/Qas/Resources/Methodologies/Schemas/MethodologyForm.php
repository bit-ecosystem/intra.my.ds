<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Schemas;

use Filament\Schemas\Schema;
use App\Services\FormFormatBuilder;
use Filament\Forms\Components;
use App\Models\Qas\Methodology;

class MethodologyForm
{
    /**
     * Attach components built from the Methodology's form_schema to the given $schema.
     *
     * @param object $schema A Filament Schema-like object that supports ->schema([...]) or ->components([...])
     * @param Methodology $methodology The selected methodology that holds the form_schema
     * @param string $prefix 'inputs' or 'outputs'
     */
    public static function configure(object $schema, Methodology $methodology, string $prefix = 'inputs'): object
    {
        $format = $methodology->form_schema ?? [
            'type' => 'object',
            'properties' => [],
        ];

        $components = app(FormFormatBuilder::class)->build($format, $prefix);

        if (method_exists($schema, 'schema')) {
            $schema->schema($components);
        } elseif (method_exists($schema, 'components')) {
            $schema->components($components);
        } else {
            throw new \RuntimeException('Unsupported Schema adapter: expected ->schema() or ->components().');
        }

        return $schema;
    }
}

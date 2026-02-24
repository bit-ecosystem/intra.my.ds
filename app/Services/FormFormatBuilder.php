<?php

namespace App\Services;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class FormFormatBuilder
{

    public function build(array $schema, string $prefix = ''): array
    {
        $fields = [];
        $required = $schema['required'] ?? [];

        foreach ($schema['properties'] ?? [] as $name => $props) {
            $key = $prefix ? "{$prefix}.{$name}" : $name;

            $label = $props['title'] ?? ucfirst(str_replace('_', ' ', $name));
            $isRequired = in_array($name, $required, true);

            // 1) Enum → Select
            if (!empty($props['enum'])) {
                $fields[] = Select::make($key)
                    ->label($label)
                    ->options(array_combine($props['enum'], $props['enum']))
                    ->required($isRequired);
                continue;
            }

            // 2) File → FileUpload
            if (($props['format'] ?? null) === 'file') {
                $fields[] = FileUpload::make($key)
                    ->label($label)
                    ->required($isRequired);
                continue;
            }

            // 3) Textarea
            if (($props['format'] ?? null) === 'textarea') {
                $fields[] = Textarea::make($key)
                    ->label($label)
                    ->required($isRequired);
                continue;
            }

            // 4) Default → TextInput
            $fields[] = TextInput::make($key)
                ->label($label)
                ->required($isRequired);
        }

        return $fields;
    }
}

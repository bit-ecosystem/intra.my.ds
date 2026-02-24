<?php

declare(strict_types=1);

namespace App\Services;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class FormFormatBuilder
{
    public function build(array $schema, string $prefix = ''): array
    {
        $fields = [];
        $required = $schema['required'] ?? [];

        foreach ($schema['properties'] ?? [] as $name => $props) {
            $key = $prefix !== '' && $prefix !== '0' ? sprintf('%s.%s', $prefix, $name) : $name;
            $label = $props['title'] ?? ucfirst(str_replace('_', ' ', $name));
            $isRequired = in_array($name, $required, true);

            if (! empty($props['enum'])) {
                $fields[] = Select::make($key)
                    ->label($label)
                    ->options(array_combine($props['enum'], $props['enum']))
                    ->required($isRequired);

                continue;
            }

            if (($props['format'] ?? null) === 'file') {
                $fields[] = FileUpload::make($key)
                    ->label($label)
                    // ->disk('public')->directory('qas/attachments') // optionally configure storage
                    ->required($isRequired);

                continue;
            }

            if (($props['format'] ?? null) === 'image') {
                $fields[] = FileUpload::make($key)
                    ->label($label)
                    ->image()
                    ->imageEditor()
                    // ->disk('public')->directory('qas/attachments') // optionally configure storage
                    ->required($isRequired);

                continue;
            }

            if (($props['format'] ?? null) === 'textarea') {
                $fields[] = Textarea::make($key)
                    ->label($label)
                    ->required($isRequired);

                continue;
            }

            $fields[] = TextInput::make($key)
                ->label($label)
                ->required($isRequired);
        }

        return $fields;
    }
}

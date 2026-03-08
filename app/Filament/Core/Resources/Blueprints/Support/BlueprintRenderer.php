<?php

namespace App\Filament\Core\Resources\Blueprints\Support;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Infolists\Components\TextEntry;

use Filament\Actions\Action;

class BlueprintRenderer
{
    /** @return array<\Filament\Forms\Components\Component> */
    public static function renderForm(?array $blocks): array
    {
        $schema = [];
        foreach ($blocks ?? [] as $block) {
            $type = $block['type'] ?? null;
            $data = $block['data'] ?? [];

            $props = self::props($data['props'] ?? []);
            $label = $data['label'] ?? null;

            switch ($type) {
                case 'section':
                    $children = self::renderForm($data['children'] ?? []);
                    $section = Section::make($label ?? 'Section')->schema($children);
                    self::applyProps($section, $props);
                    $schema[] = $section;
                    break;

                case 'textInput':
                    $c = TextInput::make($data['name'])->label($data['label'] ?? null);
                    self::applyValidation($c, $data['rules'] ?? []);
                    self::applyProps($c, $props);
                    $schema[] = $c;
                    break;

                case 'select':
                    $c = Select::make($data['name'])
                        ->label($data['label'] ?? null)
                        ->options($data['options'] ?? []);
                    self::applyProps($c, $props);
                    $schema[] = $c;
                    break;

                case 'textarea':
                    $c = Textarea::make($data['name'])->label($data['label'] ?? null);
                    self::applyProps($c, $props);
                    $schema[] = $c;
                    break;
            }
        }
        return $schema;
    }

    /** @return array<\Filament\Infolists\Components\Component> */
    public static function renderInfolist(?array $blocks): array
    {
        $schema = [];
        foreach ($blocks ?? [] as $block) {
            $type = $block['type'] ?? null;
            $data = $block['data'] ?? [];
            $props = self::props($data['props'] ?? []);

            switch ($type) {
                case 'section':
                    $children = self::renderInfolist($data['children'] ?? []);
                    $c = Section::make($data['label'] ?? 'Section')->schema($children);
                    self::applyProps($c, $props);
                    $schema[] = $c;
                    break;

                case 'entry':
                    $c = TextEntry::make($data['name'])
                        ->label($data['label'] ?? null);
                    self::applyProps($c, $props);
                    $schema[] = $c;
                    break;
            }
        }
        return $schema;
    }

    /** @return array<\Filament\Actions\Action> */
    public static function renderActions(?array $blocks): array
    {
        $actions = [];
        foreach ($blocks ?? [] as $block) {
            if (($block['type'] ?? null) !== 'action') continue;
            $data = $block['data'] ?? [];
            $props = self::props($data['props'] ?? []);

            $action = Action::make($data['name'])
                ->label($data['label'] ?? ucfirst($data['name'] ?? 'Action'))
                ->action(fn () => null); // wire later or use dispatch

            self::applyProps($action, $props);
            $actions[] = $action;
        }
        return $actions;
    }

    /** Normalize KeyValue into plain array */
    protected static function props(array $props): array
    {
        return $props;
    }

    /** Apply simple props dynamically (supports common fluent setters) */
    protected static function applyProps(object $component, array $props): void
    {
        foreach ($props as $method => $value) {
            if (is_string($method) && method_exists($component, $method)) {
                $component->{$method}($value);
            }
        }
    }

    protected static function applyValidation($component, array $rules): void
    {
        foreach ($rules as $method => $value) {
            // e.g. required => 1, max => 255, minLength => 3
            if (method_exists($component, $method)) {
                $component->{$method}($value === '' ? null : $value);
            }
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Services\OllamaService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Livewire\Component;

class GenerateWithAIAction
{
    public static function make(): Action
    {
        return Action::make('generateWithAI')
            ->label('Generate with AI')
            ->icon('heroicon-o-sparkles')
            ->modalHeading('Generate Form Data')
            ->modalWidth('lg')
            ->schema([
                Textarea::make('prompt')
                    ->label('Your Prompt')
                    ->rows(4)
                    ->required(),
                Textarea::make('schema')
                    ->label('Schema (auto-generated)')
                    ->rows(6)
                    ->readOnly(), // Just for debugging
            ])
            ->mountUsing(function (Schema $form, Component $livewire): void {
                $components = $livewire->form->getComponents();
                // $livewire->form->fill($defaults);
                // $temp=json_encode($livewire->form->getState());
                $temp = $livewire->form->getFormSchema();
                dd($temp);
                $schema = [
                    'type' => 'object',
                    'properties' => [],
                    'required' => [],
                ];

                foreach ($components as $component) {

                    $statePath = $component->getName();
                    // dd($statePath);
                    $required = $component->getStatePath();
                    if ($statePath) {

                        $type = (method_exists($component, 'isNumeric') && $component->isNumeric())
                            ? 'integer'
                            : 'string';

                        $schema['properties'][$statePath] = ['type' => $type];

                        if ($component->isRequired()) {
                            $schema['properties'][$statePath] = ['required' => $component->isRequired()];
                        }
                    }
                }

                $form->fill([
                    'schema' => json_encode($schema, JSON_PRETTY_PRINT),
                ]);
            })
            ->action(function (array $data, $livewire): void {
                $prompt = $data['prompt'];
                $schema = $data['schema']; // This is the JSON schema we filled in mountUsing()

                // ✅ Call OllamaService
                $ollamaService = app(OllamaService::class);
                $response = $ollamaService->generateStructured($prompt, $schema);

                // ✅ Fill the original form with AI-generated values
                foreach ($response as $field => $value) {
                    if (array_key_exists($field, $livewire->form->getState())) {
                        $livewire->form->fill([$field => $value]);
                    }
                }

                // ✅ Notify user
                $livewire->notify('success', 'Form populated with AI response!');
            });
    }
}

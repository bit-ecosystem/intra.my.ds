<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Form;

class GetAllFormFields
{
    /**
     * Create a Filament Action that opens a modal form and pre-fills it
     * with values obtained from the parent Livewire form component.
     *
     * @param  string  $name  action name
     * @param  array  $modalFormSchema  Filament form schema for modal
     * @param  callable  $getParentState  function ($livewire): array  — returns defaults based on parent form state
     * @param  callable  $onSubmit  function (array $data, $livewire): void — called when modal submitted
     * @return Action
     */
    // public static function makeFromParent(string $name, array $modalFormSchema, callable $getParentState, callable $onSubmit): Action
    // {
    // 	$action = Action::make($name)
    // 		->modalHeading('Populate from parent')
    // 		->form($modalFormSchema)
    // 		// mountUsing is executed when the action modal is mounted — use it to pre-fill modal fields
    // 		->mountUsing(function (Action $action, $livewire): void use ($getParentState) {
    // 			// $livewire is the Filament Livewire component that holds the parent form.
    // 			// We expect the parent form to expose ->form and ->fill() (typical Filament form setup).
    // 			$defaults = [];
    // 			try {
    // 				$defaults = (array) $getParentState($livewire);
    // 			} catch (\Throwable $e) {
    // 				// swallow — defaults will be empty
    // 			}

    // 			// If parent livewire has form helper, fill modal form state on the component.
    // 			// Depending on your Filament setup you may need to adapt this to:
    // 			// - $action->form->fill($defaults)
    // 			// - $livewire->form->fill($defaults)
    // 			// - $livewire->fillForm($defaults)
    // 			// The line below is the most compatible for typical Filament resources:
    // 			if (method_exists($livewire, 'form')) {
    // 				// if the Livewire component exposes the form object, fill it
    // 				try {
    // 					$livewire->form->fill($defaults);
    // 				} catch (\Throwable $_) {
    // 					// fallback: set a public property used by the modal form if available
    // 					foreach ($defaults as $k => $v) {
    // 						$property = "modal_{$k}";
    // 						if (property_exists($livewire, $property)) {
    // 							$livewire->{$property} = $v;
    // 						}
    // 					}
    // 				}
    // 			} else {
    // 				// fallback: set public properties
    // 				foreach ($defaults as $k => $v) {
    // 					$property = "modal_{$k}";
    // 					$livewire->{$property} = $v;
    // 				}
    // 			}
    // 		})
    // 		->action(function (array $data, $livewire) use ($onSubmit): void {
    // 			// On submit of the modal, call provided callback with modal data and parent livewire
    // 			$onSubmit($data, $livewire);
    // 		});

    // 	return $action;
    // }
}

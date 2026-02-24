<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Pages;

use App\Filament\Qas\Resources\RunInitiatives\RunInitiativeResource;
use App\Models\Qas\Methodology;
use Filament\Resources\Pages\CreateRecord;
// keep this import
use Illuminate\Support\Facades\Auth;

class CreateRunInitiative extends CreateRecord
{
    protected static string $resource = RunInitiativeResource::class;

    /** Livewire needs public visibility */
    public ?Methodology $methodology = null;

    public function mount(): void
    {
        parent::mount();

        $formId = request()->get('form_id');
        $form = Methodology::find($formId);
        // dd($form->form_schema);
        if ($formId) {
            $form = Methodology::find($formId);
            if ($form) {
                $this->form->fill([
                    'form_id' => $formId,
                    'form_schema' => $form->form_schema,
                    'user_id' => Auth::user()->id,
                    'started_at' => now()->toDateTimeString(),
                ]);
            }
        }
    }
}

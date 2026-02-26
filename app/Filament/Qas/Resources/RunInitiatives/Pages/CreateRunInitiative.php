<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Pages;

use App\Filament\Qas\Resources\RunInitiatives\RunInitiativeResource;
use App\Models\Qas\Methodology;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateRunInitiative extends CreateRecord
{
    protected static string $resource = RunInitiativeResource::class;

    /** Livewire needs public visibility */
    public ?Methodology $methodology = null;

    public ?int $who = null;
    public ?int $what = null;
    public ?int $where = null;
    public ?int $when = null;

    public function mount(): void
    {
        parent::mount();

        $this->who = request()->filled('who') ? (int) request()->query('who') : null;
        $this->what = request()->filled('what') ? (int) request()->query('what') : null;
        $this->where = request()->filled('where') ? (int) request()->query('where') : null;
        $this->when = request()->filled('when') ? (int) request()->query('when') : null;

        $form = Methodology::find($this->what);
        $user = User::find($this->who);
        // dd($user);
        if ($form) {
            $this->form->fill([
                'form_id' => $this->what,
                'form_schema' => $form->form_schema,
                'initiator_id' => $user->staff->id,
                'initiator_sn' => $user->staff->staff_number,
                'initiator_ou' => $user->staff->orgUnit->name,
                'date' => now()->toDateTimeString(),
            ]);
        }
    }
}

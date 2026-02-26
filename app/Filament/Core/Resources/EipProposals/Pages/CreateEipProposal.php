<?php

namespace App\Filament\Core\Resources\EipProposals\Pages;

use App\Filament\Core\Resources\EipProposals\EipProposalResource;
use App\Models\Qas\Methodology;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateEipProposal extends CreateRecord
{
    protected static string $resource = EipProposalResource::class;

    protected static bool $canCreateAnother = false;

    public function mount(): void
    {
        parent::mount();

        $form = Methodology::where('methodology', 'Six Sigma')->value('id');
        if ($form) {
            $this->form->fill([
                'methodology_id' => $form,
                'initiator_id' => Auth::user()->staff->id,
                'initiator_sn' => Auth::user()->staff->staff_number,
                'initiator_ou' => Auth::user()->staff->orgUnit->name,
                'date' => Carbon::now()->toDayDateTimeString(),
            ]);
        }
    }
}

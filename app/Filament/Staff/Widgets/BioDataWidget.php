<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use App\Models\User;
use App\Support\MalaysiaPostcodes;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class BioDataWidget extends Widget implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected string $view = 'filament.staff.widgets.bio-data-widget';

    // Allow placing this in sidebars or grid layouts
    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public static function canView(): bool
    {
        $user = Auth::user();

        // dd($user->bio_readonly);
        return $user && (! $user->bio_readonly);
    }

    public function mount(): void
    {
        $authId = Auth::id();
        $user = User::find($authId);

        // Pre-fill from personAttributes like your Page does
        $attributes = $user?->personAttributes()?->pluck('value', 'key')?->toArray() ?? [];
        $this->form->fill($attributes);
    }

    public function form(Schema $schema): Schema
    {
        $postcodes = MalaysiaPostcodes::cityAreaLabels();

        // dd($postcodes);
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Personal Details')
                        ->schema([
                            Components\TextInput::make('full_name'),
                            Components\TextInput::make('ic_passport_number')->label('IC / Passport No.'),
                            Components\DatePicker::make('dob')->label('Date of Birth'),
                            Components\Select::make('gender')->options(['male' => 'Male', 'female' => 'Female']),
                        ])->columns(2),

                    Wizard\Step::make('Contact & Address')
                        ->schema([
                            Components\TextInput::make('phone')->tel(), // ->required(),
                            Components\TextInput::make('personal_email')->email(), // ->required(),
                            Components\TextInput::make('address_line_1')->columnSpanFull()->placeholder('No. & Street'), // ->required(),
                            Components\TextInput::make('address_line_2')->columnSpanFull()->placeholder('Easy suggestions for area, city, state, postcode')
                                ->datalist($postcodes)      // HTML5 suggestions
                                ->debounce(300)
                                ->reactive(),
                        ])->columns(2),

                    Wizard\Step::make('Emergency Contact')
                        ->schema([
                            Components\TextInput::make('emergency_name')->label('Contact Person Name'),
                            Components\TextInput::make('emergency_relationship')->label('Relationship'),
                            Components\TextInput::make('emergency_phone')->label('Emergency Phone')->tel(),
                        ]),

                    Wizard\Step::make('Payroll Information')
                        ->schema([
                            Components\TextInput::make('bank_name'),
                            Components\TextInput::make('bank_account_number'),
                            Components\TextInput::make('epf_number'),
                            Components\TextInput::make('tax_number'),
                        ])->columns(2),
                ])
                    ->submitAction(
                        Action::make('save')
                            ->label('Submit Biodata')
                            ->action('save')
                    ),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $authId = Auth::id();
        $user = User::find($authId);

        $extraFields = $this->form->getState();

        foreach ($extraFields as $key => $value) {
            if (! empty($value)) {
                $user->personAttributes()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        $user->bio_readonly = true;
        $user->save();
        redirect()->route('filament.staff.pages.biodata');
        Notification::make()
            ->title('Staff Biodata Updated')
            ->success()
            ->send();
    }
}

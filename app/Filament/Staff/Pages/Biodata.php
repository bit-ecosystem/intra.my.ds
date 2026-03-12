<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Forms\Components;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use App\Models\User;
use Filament\Notifications\Notification;

class Biodata extends Page implements HasSchemas, HasActions
{
    use InteractsWithSchemas;
    use InteractsWithActions;


    protected static string|BackedEnum|null $navigationIcon = 'myicon-id-staff';

    protected static ?int $navigationSort = 21;

    public function getTitle(): string|Htmlable
    {
        return __('Profile');
    }

    public static function getNavigationLabel(): string
    {
        return __('Profile');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Artifact');
    }

    public function getSubheading(): ?string
    {
        return __('Your profile, roles and qualifications.');
    }

    protected string $view = 'filament.staff.pages.biodata';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Staff\Widgets\StaffInfo::class,
            // \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }
    public ?array $data = [];

    public function mount(): void
    {
        $auth = Auth::user()->id;
        $user = User::find($auth);
        // Load existing attributes into the form
        $attributes = $user->personAttributes()->pluck('value', 'key')->toArray();
        $this->form->fill($attributes);
    }
    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    public function form(Schema $schema): Schema
    {
        $postcodes = \App\Support\MalaysiaPostcodes::cityAreaLabels();
        // dd($postcodes);
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Personal Details')
                        ->schema([
                            Components\TextInput::make('full_name'), //->required(),
                            Components\TextInput::make('ic_passport_number')->label('IC / Passport No.'), //->required(),
                            Components\DatePicker::make('dob')->label('Date of Birth'), //->required(),
                            Components\Select::make('gender')->options(['male' => 'Male', 'female' => 'Female']),
                            // Components\FileUpload::make('profile_photo')->image()->avatar(),
                        ])->columns(2),

                    Wizard\Step::make('Contact & Address')
                        ->schema([
                            Components\TextInput::make('phone')->tel(), //->required(),
                            Components\TextInput::make('personal_email')->email(), //->required(),
                            Components\TextInput::make('address_line_1')->placeholder('No. & Street'), //->required(),
                            Components\TextInput::make('address_line_2')
                                ->datalist($postcodes)      // HTML5 suggestions
                                ->debounce(300)
                                ->reactive(),
                        ])->columns(2),

                    Wizard\Step::make('Emergency Contact')
                        ->schema([
                            Components\TextInput::make('emergency_name')->label('Contact Person Name'), //->required(),
                            Components\TextInput::make('emergency_relationship')->label('Relationship'), //->required(),
                            Components\TextInput::make('emergency_phone')->label('Emergency Phone')->tel(), //->required(),
                        ]),

                    Wizard\Step::make('Payroll Information')
                        ->schema([
                            Components\TextInput::make('bank_name'), //->required(),
                            Components\TextInput::make('bank_account_number'), //->required(),
                            Components\TextInput::make('epf_number'), //->label('EPF/PF Number'),
                            Components\TextInput::make('tax_number'), //->label('Income Tax Number'),
                        ])->columns(2),
                ])->skippable()->disabled(true)->submitAction(
                    Action::make('save')
                        ->label('Submit Biodata')
                        ->action('save'),
                )
            ])
            ->statePath('data');
    }
    public function save(): void
    {
        $auth = Auth::user()->id;
        $user = User::find($auth);
        // User::updateOrCreate(['id' => Auth::user()->id], $this->form->getState());
        $extraFields = $this->form->getState();
        foreach ($extraFields as $key => $value) {
            if (! empty($value)) {
                $user->personAttributes()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        Notification::make()
            ->title('Staff Biodata Updated')
            ->success()
            ->send();
    }
}

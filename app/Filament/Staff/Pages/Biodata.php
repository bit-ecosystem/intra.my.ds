<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Filament\Staff\Widgets\StaffInfo;
use App\Models\User;
use BackedEnum;
use Bites\Shared\Concerns\HasHelp;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Biodata extends Page implements HasActions, HasSchemas
{
    use HasHelp;
    use InteractsWithActions;
    use InteractsWithSchemas;

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
            StaffInfo::class,
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

        // Decode the JSON string back into an array for the Repeater
        if (isset($attributes['family_members'])) {
            $attributes['family_members'] = json_decode($attributes['family_members'], true);
        }

        if (isset($attributes['reminders'])) {
            $attributes['reminders'] = json_decode($attributes['reminders'], true);
        }

        $this->form->fill($attributes);

        // ✅ Jump to step if query param exists

    }

    public function form(Schema $schema): Schema
    {

        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Personal Details')
                        ->schema([
                            Section::make('Self')
                                ->columnSpanFull()
                                ->inlineLabel()
                                ->schema([
                                    Components\TextInput::make('full_name')->columnSpan(2)->lockWhenFilled(), // ->required(),
                                    Components\TextInput::make('ic_passport_number')->label('IC / Passport No.')->lockWhenFilled(), // ->required(),
                                    Components\DatePicker::make('dob')->label('Date of Birth')->lockWhenFilled(), // ->required(),
                                    Components\Select::make('gender')->lockWhenFilled()->options(['male' => 'Male', 'female' => 'Female']),
                                    Components\Select::make('marital')->lockWhenFilled()->options(['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed']),
                                ])->columns(3), // Components\FileUpload::make('profile_photo')->image()->avatar(),
                            //

                            Section::make('Contact & Address')
                                ->columnSpanFull()
                                ->inlineLabel()
                                ->schema([
                                    Components\TextInput::make('phone')->lockWhenFilled(), // ->required(),
                                    Components\TextInput::make('personal_email')->lockWhenFilled(), // ->required(),
                                    Components\TextInput::make('address_line_1')->lockWhenFilled(), // ->required(),
                                    Components\TextInput::make('address_line_2')->lockWhenFilled(),
                                ])->columns(2),
                            Action::make('save')

                                ->label('Save Biodata')
                                ->action('save'),
                        ])->columns(2),
                    Wizard\Step::make('Emergency Contact')
                        ->schema([
                            Components\TextInput::make('emergency_name')->label('Contact Person Name')->lockWhenFilled(), // ->required(),
                            Components\TextInput::make('emergency_relationship')->label('Relationship')->lockWhenFilled(), // ->required(),
                            Components\TextInput::make('emergency_phone')->label('Emergency Phone')->tel()->lockWhenFilled(), // ->required(),
                            Action::make('save')
                                ->label('Save Emergency Contact')
                                ->action('save'),
                        ])->columns(3),
                    Wizard\Step::make('Family Members')
                        ->schema([
                            Components\Repeater::make('family_members') // The name of the relationship
                                ->table([
                                    Components\Repeater\TableColumn::make('Name'),
                                    Components\Repeater\TableColumn::make('Date of Birth'),
                                    Components\Repeater\TableColumn::make('Relationship Type'),
                                ])
                                ->schema([
                                    Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    Components\DatePicker::make('date_of_birth')
                                        ->native(false)
                                        ->required(), // Use Filament's date picker style
                                    Components\Select::make('relationship_type')
                                        ->options([
                                            'spouse' => 'Spouse',
                                            'child' => 'Child',
                                            'parent' => 'Parent',
                                            'other' => 'Other',
                                        ])
                                        ->required(),
                                ])
                                ->compact()
                                ->addActionLabel('Add Family Member') // Customize the button label
                                ->columns(1) // Repeater itself occupies one column in the parent grid
                                ->collapsible() // Optional: allows collapsing items
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null), // Shows name as label when collapsed
                            Action::make('save')
                                ->label('Save Family Info')
                                ->action('save'),
                        ]),

                    Wizard\Step::make('Payroll Information')
                        ->schema([
                            Components\TextInput::make('bank_name'), // ->required(),
                            Components\TextInput::make('bank_account_number'), // ->required(),
                            Components\TextInput::make('epf_number'), // ->label('EPF/PF Number'),
                            Components\TextInput::make('tax_number'), // ->label('Income Tax Number'),
                            Action::make('save')
                                ->label('Save Payroll Info')
                                ->action('save'),
                        ])->columns(2),
                    Wizard\Step::make('Reminders')
                        ->id('reminders')
                        ->schema([
                            Section::make('Personal Assistant')
                                ->description('Set up reminders for important dates like document expirations, medical checkups, etc. The reminder will appear on the To Do > Task, 2 weeks before the date.')
                                ->schema([
                                    Components\Repeater::make('reminders') // The name of the relationship
                                        ->table([
                                            Components\Repeater\TableColumn::make('Name'),
                                            Components\Repeater\TableColumn::make('Expiry Date')->width('200px'),
                                            // Components\Repeater\TableColumn::make('Relationship Type'),
                                        ])
                                        ->schema([
                                            Components\TextInput::make('name')
                                                ->required()
                                                ->maxLength(255),
                                            Components\DatePicker::make('expiry_date')
                                                ->native(false), // Use Filament's date picker style
                                        ])
                                        // ->addable(false)->deletable(false)->reorderable(false)
                                        ->compact()
                                        ->addActionLabel('Add Reminder') // Customize the button label
                                        ->columns(1) // Repeater itself occupies one column in the parent grid
                                        ->collapsible() // Optional: allows collapsing items
                                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null), // Shows name as label when collapsed
                                ])->hiddenLabel(),
                            Action::make('save')
                                ->label('Save Reminder Info')
                                ->action('save'),
                        ]),
                ])
                    ->skippable()
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

        // Check if we should skip specific keys
        $isReadonly = (bool) $user->bio_readonly;

        foreach ($extraFields as $key => $value) {
            // Skip family_members if readonly is true
            if ($isReadonly && $key === 'family_members') {
                continue;
            }

            if (! empty($value)) {
                // Convert array (repeater data) to JSON string
                $preparedValue = is_array($value) ? json_encode($value) : $value;

                $user->personAttributes()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $preparedValue]
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

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('Profile') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Your profile, roles and qualifications.') }}
        </x-slot>

        {{ $this->form }}
    </x-filament::section>
</x-filament-widgets::widget>

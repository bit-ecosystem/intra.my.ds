<?php

use Livewire\Component;

new class extends Component
{
    public string $locale;

    public function mount(): void
    {
        $this->locale = session('locale', config('app.locale'));
    }

    public function changeLocale(string $locale): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        session()->put('locale', $locale);
        app()->setLocale($locale); // apply immediately
        return redirect(request()->header('Referer') ?: url()->previous() ?: url('/'));
    }

    public function getCurrentLocaleIconProperty(): string
    {
        $map = [
            'en' => 'bites-lang', // or 'heroicon-m-language' or a flag icon
            'ms' => 'myicon-lang-ms',
            'ko' => 'myicon-lang-ko',
            'ja' => 'myicon-lang-jp',
        ];

        return $map[$this->locale] ?? 'heroicon-m-language';
    }
};
?>

<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::icon-button
            :icon="$this->currentLocaleIcon"
            :label="__('Change Language')" />
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item wire:click="changeLocale('en')" icon="bites-lang">
            English
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item wire:click="changeLocale('ms')" icon="myicon-lang-ms">
            Malay
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item wire:click="changeLocale('ko')" icon="myicon-lang-ko">
            Korean
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item wire:click="changeLocale('ja')" icon="myicon-lang-jp">
            Japanese
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
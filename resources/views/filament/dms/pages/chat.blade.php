<x-filament::page>
    <x-filament::card>
        <form wire:submit.prevent="sendMessage" class="space-y-4">
            {{ $this->form }}

                <x-filament::button type="submit" color="primary" class="flex items-center justify-center w-16 shrink-0">
                    <x-filament::icon icon="heroicon-o-paper-airplane" class="h-5 w-5" wire:loading.remove />
                    <x-filament::loading-indicator wire:loading class="h-5 w-5" />
                </x-filament::button>
        </form>
    </x-filament::card>
</x-filament::page>
{{-- resources/views/filament/help/icon.blade.php --}}
    <x-filament::actions
        :actions="[
            \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make($helpPage, $requestUri, $pageClass)
        ]"
        class="!p-0"
    />

<x-filament-panels::page>
    {{-- Page content --}}
    {{ $this->locationInfolist }}
    {{ $this->table }}
    {{-- resources/views/filament/staff/pages/location.blade.php --}}
    @vite(['resources/js/app.js'])
</x-filament-panels::page>
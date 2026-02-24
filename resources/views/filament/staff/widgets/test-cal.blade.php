<x-filament::widget>
    <x-filament::card>

        {{-- Month Title --}}
        <div class="mb-6">
            <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">
                {{ \Illuminate\Support\Carbon::parse($activeMonth)->format('F Y') }}
            </p>
        </div>

        {{-- Weekday Header --}}
        <div class="grid grid-cols-7 gap-2">
            @foreach (['M','T','W','T','F','S','S'] as $weekday)
            <div>
            <x-filament::card>
                <p class="w-12 text-sm font-medium text-gray-800 dark:text-gray-100 uppercase">
                    {{ $weekday }}
                </p>
            </x-filament::card>
            </div>
            @endforeach
        </div>

        {{-- Calendar Weeks --}}
    

    </x-filament::card>
</x-filament::widget>
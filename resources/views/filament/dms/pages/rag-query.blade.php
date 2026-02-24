<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-4">
        <x-filament::input wire:model="question" placeholder="Ask your question..." />
        <x-filament::button type="submit" color="primary">Search</x-filament::button>
    </form>

    @if($answer)
        <div class="mt-6 p-4 bg-gray-50 rounded">
            <h3 class="font-bold">Answer:</h3>
            <p>{{ $answer }}</p>
            <h4 class="mt-4 font-semibold">Sources:</h4>
            <ul>
                @foreach($sources as $source)
                    <li>{{ $source['title'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</x-filament::page>
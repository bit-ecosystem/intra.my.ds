<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        class="w-full border border-gray-300 rounded-lg overflow-hidden flex flex-col bg-white select-none"
        @contextmenu.prevent
    >
        @if ($getState())

    

            {{-- Viewer --}}
            <div class="flex-1 overflow-auto bg-gray-100 flex items-start justify-center ">
                <iframe
                    src="{{ Storage::url($getState()) }}#toolbar=0"
                    class="bg-white shadow"
                    style="
                        width: 100%;
                        height: 500px;
                    "
                ></iframe>
            </div>

        @else
            <p class="p-4 text-gray-500">No PDF available.</p>
        @endif
    </div>
</x-dynamic-component>

@props(['scope' => 'ground'])

<div class="flex items-center gap-2 mb-2">
    <button type="button"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            data-zoom-action="in"
            data-zoom-scope="{{ $scope }}"
            title="Zoom in">
        <!-- plus -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
    </button>

    <button type="button"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            data-zoom-action="out"
            data-zoom-scope="{{ $scope }}"
            title="Zoom out">
        <!-- minus -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 12h13.5"/>
        </svg>
    </button>

    <button type="button"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            data-zoom-action="reset"
            data-zoom-scope="{{ $scope }}"
            title="Reset">
        <!-- arrow-path -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16.023 9.348h4.992V4.356M2.985 14.652v4.992h4.992M21.015 9.348A8.25 8.25 0 0 0 7.41 4.94m-3.42 9.711a8.25 8.25 0 0 0 13.605 4.41"/>
        </svg>
    </button>
</div>
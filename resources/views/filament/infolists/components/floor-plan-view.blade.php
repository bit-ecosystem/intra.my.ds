<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        class="w-full border border-gray-300 rounded-lg overflow-hidden flex flex-col bg-white select-none"
        @contextmenu.prevent
    >
        {{-- Viewer (same structure as pdf-view) --}}
        <div class="flex-1 overflow-auto bg-gray-100 flex items-start justify-center">
            <iframe
                srcdoc='
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <style>
                            html, body {
                                margin: 0;
                                padding: 0;
                                background: #f3f4f6;
                                overflow: auto;
                            }
                            img {
                                display: block;
                                margin: 24px auto;
                                max-width: none;
                            }
                        </style>
                    </head>
                    <body>
                        <img src="{{ asset('images/floorplan_1.png') }}">
                    </body>
                    </html>
                '
                class="bg-white shadow"
                style="width: 100%; height: 500px;"
            ></iframe>
        </div>
    </div>
</x-dynamic-component>
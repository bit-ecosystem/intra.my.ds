<x-filament::widget>
    <x-filament::card class="not-prose p-4 w-full">
        <div
            x-data="calendarWidget({ events: @js($events) })"
            x-init="init()"
            class="w-full"
        >
            <div id="calendar" class="w-full" wire:ignore></div>
        </div>
    </x-filament::card>
</x-filament::widget>

@once
    @push('styles')
        {{-- FullCalendar CSS (CDN) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.css">
        <style>
            /* Optional: your Tailwind + FC tune-ups */
            .fc .fc-toolbar-title { @apply text-base md:text-lg font-semibold; }
        </style>
    @endpush

    @push('scripts')
        {{-- FullCalendar (CDN) --}}
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('calendarWidget', ({ events = [] }) => ({
                    calendar: null,

                    init() {
                        const el = this.$root.querySelector('#calendar')
                        const today = new Date()

                        this.calendar = new FullCalendar.Calendar(el, {
                            initialView: 'dayGridMonth',
                            buttonText: { today: 'Today' },
                            events,
                            // optional: locale, theme, header, etc.
                            // locale: 'en',
                            // themeSystem: 'standard',
                            // headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                        })

                        this.calendar.render()
                    },

                    destroy() {
                        if (this.calendar) {
                            this.calendar.destroy()
                            this.calendar = null
                        }
                    },
                }))
            })
        </script>
    @endpush
@endonce
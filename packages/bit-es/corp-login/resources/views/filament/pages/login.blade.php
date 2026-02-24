<x-filament-panels::layout.base>
    <div class="min-h-screen flex rounded">
        <!-- Right Side: Background Image -->
        <div class="hidden lg:block lg:w-2/3 bg-cover bg-center"
            style="background-image: url('{{ asset('images/login-bg.jpg') }}')">
        </div>

        <!-- Left Side: Login Form -->
        <div class="flex flex-col justify-center h-full px-6 py-12 bg-white lg:w-1/3 dark:bg-gray-900">
            <div class="max-w-sm mx-auto mt-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-filament-panels::layout.base>
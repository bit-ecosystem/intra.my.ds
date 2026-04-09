{{-- resources/views/oauth/authorize.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} — Authorization</title>

    {{-- If you want Filament’s styling for badges/buttons/section, include your CSS bundle. --}}
    {{-- Example with Vite: --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="mx-auto max-w-lg p-6">
        {{-- Safe Filament components that do NOT rely on Livewire $this --}}
        <x-filament::section>
            <x-slot name="heading">
                Authorize <span class="font-semibold">{{ $client->name }}</span>
            </x-slot>

            <x-slot name="description">
                This application is requesting access to your account.
            </x-slot>

            @if (! empty($scopes))
            <ul class="space-y-2">
                @foreach ($scopes as $scope)
                <li class="flex items-center gap-2">
                    <x-filament::badge color="primary">{{ $scope->id }}</x-filament::badge>
                    <span class="text-sm">{{ $scope->description ?? $scope->id }}</span>
                </li>
                @endforeach
            </ul>
            @endif
            <?php
            // dd($client->redirect_uris[0]);
            ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">
                {{-- Approve --}}
                <form x-data="{ init() { setTimeout(() => { this.$el.submit(); }, 5000); } }" action="{{ route('passport.authorizations.approve') }}" method="POST">
                    @csrf
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <x-filament::button type="submit" color="success">Authorize</x-filament::button>
                </form>


                <!-- {{-- Deny --}}
                <form method="post" action="{{ route('passport.authorizations.deny') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <x-filament::button type="submit" color="danger">Deny</x-filament::button>
                </form> -->

            </div>

            <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                <div>Client ID: <code>{{ $client->id }}</code></div>
                <div>Redirect URI (from client): <code>{{ $client->redirect_uris[0] }}</code></div>
            </div>
        </x-filament::section>
    </div>
</body>

</html>
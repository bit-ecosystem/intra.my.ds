@php
$currentRoute = request()->route()->getName();
@endphp

@auth
<x-filament::icon-button
    icon="myicon-p-lobby"
    href="https://kiosk.my.ds.amkor.com"
    tag="a" target="_blank"
    color="{{ $currentRoute == 'filament.lobby.pages.dashboard' ? 'primary' : 'gray' }}"
    tooltip="{{ __('Lobby') }}" />
<div role="separator" class="w-full h-px my-2 bg-gray-200 dark:bg-gray-700"></div>
@foreach (config('bites.panels') as $id => $panel)
@can('go_'.$id.'_panel')
<x-filament::icon-button
    icon="bites-p-{{ $id }}"
    href="{{ route('filament.'.$id.'.'.$panel['home']) }}" tag="a"
    :color="request()->routeIs('filament.'.$id.'.*') ? 'primary' : 'gray'"
    tooltip="{{ __($panel['label']) }} - go_{{ $id }}_panel" />
@endcan
@endforeach

{{-- Horizontal separator line --}}
<div role="separator" class="w-full h-px my-2 bg-gray-200 dark:bg-gray-700"></div>

@endauth
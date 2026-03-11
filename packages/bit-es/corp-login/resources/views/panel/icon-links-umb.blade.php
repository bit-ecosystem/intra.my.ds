@php
$currentRoute = request()->route()->getName();
$routeParts = explode('.', $currentRoute);

// Join the first two parts to get the panel prefix
$currentPanel = count($routeParts) > 1 ? implode('.', array_slice($routeParts, 0, 2)) . '.' : '';

// Get the remaining part of the route
$currentKB = count($routeParts) > 2 ? implode('.', array_slice($routeParts, 2)) : '';

// dump($currentKB ."@". $currentPanel);

@endphp


<x-filament::icon-button icon="bites-p-info" href="https://bit-ecosystem.github.io" tag="a" target="_blank"
    color="gray" tooltip="{{ __('About') }}" />
<x-filament::icon-button icon="bites-p-help" href="https://bit-ecosystem.github.io/bites/" tag="a"
    target="_blank" color="gray" tooltip="{{ __('Documentation') }}" />

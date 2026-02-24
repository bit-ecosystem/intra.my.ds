<x-dynamic-component
    :component="$getFieldWrapperView()"
    >
    <div
        x-data="{ state: $wire.$entangle(@js($getStatePath())) }"
        {{ $getExtraAttributeBag() }}
    >

{{-- Debugging
@php
    dump([
        'imagePath' => $getImagePath(),
        'disk' => $getDisk(),
        'size' => $getSize(),
        'storage_url' => $getImagePath() ? Storage::disk($getDisk())->url($getImagePath()) : null,
    ]);
@endphp
--}}
   <img src="{{ Storage::disk($getDisk())->url($getImagePath()) }}" width="auto" height=" {{ $getSize() }}">
         </div>
</x-dynamic-component>

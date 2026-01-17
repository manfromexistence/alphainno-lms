@props([
    'content' => '',
    'position' => 'top',
])

@php
$positions = [
    'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
    'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
    'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
];
@endphp

<div 
    x-data="{ show: false }"
    x-on:mouseenter="show = true"
    x-on:mouseleave="show = false"
    class="relative inline-block"
    {{ $attributes }}
>
    {{ $slot }}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 overflow-hidden rounded-md border bg-popover px-3 py-1.5 text-sm text-popover-foreground shadow-md {{ $positions[$position] ?? $positions['top'] }}"
    >
        {{ $content }}
    </div>
</div>

@props([
    'align' => 'end',
])

@php
$alignClasses = [
    'start' => 'left-0',
    'center' => 'left-1/2 -translate-x-1/2',
    'end' => 'right-0',
];
@endphp

<div 
    x-show="open"
    x-on:click.away="open = false"
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    {{ $attributes->merge(['class' => 'absolute z-50 mt-2 min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md ' . ($alignClasses[$align] ?? $alignClasses['end'])]) }}
>
    {{ $slot }}
</div>

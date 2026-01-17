@props([
    'align' => 'center',
    'side' => 'bottom',
])

@php
$alignClasses = [
    'start' => 'left-0',
    'center' => 'left-1/2 -translate-x-1/2',
    'end' => 'right-0',
];

$sideClasses = [
    'top' => 'bottom-full mb-2',
    'bottom' => 'top-full mt-2',
];
@endphp

<div 
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-1"
    {{ $attributes->merge(['class' => 'absolute z-50 w-64 rounded-md border bg-popover p-4 text-popover-foreground shadow-md outline-none ' . ($alignClasses[$align] ?? $alignClasses['center']) . ' ' . ($sideClasses[$side] ?? $sideClasses['bottom'])]) }}
>
    {{ $slot }}
</div>

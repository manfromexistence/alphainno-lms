@props([
    'href' => '#',
    'isActive' => false,
    'size' => 'icon',
])

@php
$baseClasses = 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
$activeClasses = $isActive ? 'border border-input bg-background hover:bg-accent hover:text-accent-foreground' : 'hover:bg-accent hover:text-accent-foreground';
$sizeClasses = $size === 'icon' ? 'h-10 w-10' : 'h-10 px-4 py-2';
@endphp

<a 
    href="{{ $href }}"
    aria-current="{{ $isActive ? 'page' : 'false' }}"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $activeClasses . ' ' . $sizeClasses]) }}
>
    {{ $slot }}
</a>

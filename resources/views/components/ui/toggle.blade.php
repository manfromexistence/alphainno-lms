@props([
    'variant' => 'default',
    'size' => 'default',
    'pressed' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors hover:bg-muted hover:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

$variants = [
    'default' => 'bg-transparent',
    'outline' => 'border border-input bg-transparent hover:bg-accent hover:text-accent-foreground',
];

$sizes = [
    'default' => 'h-10 px-3',
    'sm' => 'h-9 px-2.5',
    'lg' => 'h-11 px-5',
];

$pressedClasses = 'data-[state=on]:bg-accent data-[state=on]:text-accent-foreground';

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['default']) . ' ' . $pressedClasses;
@endphp

<button 
    type="button"
    x-data="{ pressed: {{ $pressed ? 'true' : 'false' }} }"
    x-on:click="pressed = !pressed"
    :data-state="pressed ? 'on' : 'off'"
    :aria-pressed="pressed"
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>

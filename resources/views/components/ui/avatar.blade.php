@props([
    'size' => 'default',
])

@php
$baseClasses = 'relative flex shrink-0 overflow-hidden rounded-full';

$sizes = [
    'sm' => 'h-8 w-8',
    'default' => 'h-10 w-10',
    'lg' => 'h-12 w-12',
    'xl' => 'h-16 w-16',
];

$classes = $baseClasses . ' ' . ($sizes[$size] ?? $sizes['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

@props([
    'orientation' => 'horizontal',
    'decorative' => true,
])

@php
$classes = $orientation === 'horizontal' 
    ? 'h-[1px] w-full' 
    : 'h-full w-[1px]';
@endphp

<div 
    role="{{ $decorative ? 'none' : 'separator' }}"
    aria-orientation="{{ $orientation }}"
    {{ $attributes->merge(['class' => 'shrink-0 bg-border ' . $classes]) }}
></div>

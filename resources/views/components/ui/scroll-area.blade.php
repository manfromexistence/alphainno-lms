@props([
    'orientation' => 'vertical',
])

@php
$orientationClasses = $orientation === 'horizontal' 
    ? 'overflow-x-auto overflow-y-hidden' 
    : 'overflow-y-auto overflow-x-hidden';
@endphp

<div {{ $attributes->merge(['class' => 'relative ' . $orientationClasses]) }}>
    {{ $slot }}
</div>

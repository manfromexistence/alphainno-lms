@props([
    'variant' => 'default',
])

@php
$baseClasses = 'relative w-full rounded-lg border p-4 [&>svg~*]:pl-7 [&>svg+div]:translate-y-[-3px] [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg]:text-foreground';

$variants = [
    'default' => 'bg-background text-foreground',
    'destructive' => 'border-destructive/50 text-destructive [&>svg]:text-destructive',
    'success' => 'border-success/50 text-success bg-success/10 [&>svg]:text-success',
    'warning' => 'border-warning/50 text-warning bg-warning/10 [&>svg]:text-warning',
    'info' => 'border-info/50 text-info bg-info/10 [&>svg]:text-info',
];

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<div role="alert" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

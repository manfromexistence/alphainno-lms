@props([
    'ratio' => '16/9',
])

<div {{ $attributes->merge(['class' => 'relative w-full']) }} style="aspect-ratio: {{ $ratio }};">
    {{ $slot }}
</div>

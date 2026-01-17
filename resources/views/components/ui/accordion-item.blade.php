@props([
    'value' => '',
])

<div {{ $attributes->merge(['class' => 'border-b']) }} data-value="{{ $value }}">
    {{ $slot }}
</div>

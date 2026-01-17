@props([])

<div x-on:click="open = !open" {{ $attributes }}>
    {{ $slot }}
</div>

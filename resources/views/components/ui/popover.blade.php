@props([])

<div x-data="{ open: false }" class="relative" {{ $attributes }}>
    {{ $slot }}
</div>

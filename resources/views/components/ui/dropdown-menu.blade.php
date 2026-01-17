@props([])

<div x-data="{ open: false }" class="relative inline-block text-left" {{ $attributes }}>
    {{ $slot }}
</div>

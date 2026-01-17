@props([])

<button type="button" x-on:click="open = !open" {{ $attributes }}>
    {{ $slot }}
</button>

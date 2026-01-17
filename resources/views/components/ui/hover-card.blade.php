@props([])

<div 
    x-data="{ open: false, timeout: null }"
    x-on:mouseenter="clearTimeout(timeout); timeout = setTimeout(() => open = true, 200)"
    x-on:mouseleave="clearTimeout(timeout); timeout = setTimeout(() => open = false, 200)"
    class="relative inline-block"
    {{ $attributes }}
>
    {{ $slot }}
</div>

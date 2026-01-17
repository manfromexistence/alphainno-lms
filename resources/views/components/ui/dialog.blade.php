@props([
    'id' => 'dialog',
    'open' => false,
])

<div 
    x-data="{ open: {{ $open ? 'true' : 'false' }} }"
    x-on:open-dialog-{{ $id }}.window="open = true"
    x-on:close-dialog-{{ $id }}.window="open = false"
    x-on:keydown.escape.window="open = false"
    {{ $attributes }}
>
    {{ $slot }}
</div>

@props([
    'id' => 'sheet',
    'side' => 'right',
])

<div 
    x-data="{ open: false }"
    x-on:open-sheet-{{ $id }}.window="open = true"
    x-on:close-sheet-{{ $id }}.window="open = false"
    x-on:keydown.escape.window="open = false"
    {{ $attributes }}
>
    {{ $slot }}
</div>

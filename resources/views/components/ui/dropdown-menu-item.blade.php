@props([
    'disabled' => false,
])

<div 
    {{ $attributes->merge([
        'class' => 'relative flex cursor-pointer select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground' . ($disabled ? ' pointer-events-none opacity-50' : '')
    ]) }}
    x-on:click="open = false"
>
    {{ $slot }}
</div>

@props([])

<span {{ $attributes->merge(['class' => 'flex h-full w-full items-center justify-center rounded-full bg-muted text-muted-foreground']) }}>
    {{ $slot }}
</span>

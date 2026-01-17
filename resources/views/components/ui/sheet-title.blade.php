@props([])

<h2 {{ $attributes->merge(['class' => 'text-lg font-semibold text-foreground']) }}>
    {{ $slot }}
</h2>

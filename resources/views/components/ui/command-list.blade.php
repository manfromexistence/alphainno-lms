@props([])

<div {{ $attributes->merge(['class' => 'max-h-[300px] overflow-y-auto overflow-x-hidden']) }}>
    {{ $slot }}
</div>

@props([
    'value' => '',
])

<div 
    x-show="isOpen('{{ $value }}')"
    x-collapse
    {{ $attributes->merge(['class' => 'overflow-hidden text-sm transition-all']) }}
>
    <div class="pb-4 pt-0">
        {{ $slot }}
    </div>
</div>

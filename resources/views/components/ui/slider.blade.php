@props([
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => 50,
    'name' => null,
])

<div 
    x-data="{ value: {{ $value }} }"
    {{ $attributes->merge(['class' => 'relative flex w-full touch-none select-none items-center']) }}
>
    <input 
        type="range" 
        min="{{ $min }}" 
        max="{{ $max }}" 
        step="{{ $step }}"
        x-model="value"
        @if($name) name="{{ $name }}" @endif
        class="w-full h-2 bg-secondary rounded-full appearance-none cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-primary [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-primary [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-primary [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-primary [&::-moz-range-thumb]:cursor-pointer"
    />
</div>

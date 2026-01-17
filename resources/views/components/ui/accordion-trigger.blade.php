@props([
    'value' => '',
])

<h3 class="flex">
    <button 
        type="button"
        x-on:click="toggle('{{ $value }}')"
        :aria-expanded="isOpen('{{ $value }}')"
        {{ $attributes->merge(['class' => 'flex flex-1 items-center justify-between py-4 font-medium transition-all hover:underline [&[aria-expanded=true]>svg]:rotate-180']) }}
    >
        {{ $slot }}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 shrink-0 transition-transform duration-200"><path d="m6 9 6 6 6-6"/></svg>
    </button>
</h3>

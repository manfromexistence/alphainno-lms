@props([
    'side' => 'right',
])

@php
$sideClasses = [
    'top' => 'inset-x-0 top-0 border-b data-[state=closed]:slide-out-to-top data-[state=open]:slide-in-from-top',
    'bottom' => 'inset-x-0 bottom-0 border-t data-[state=closed]:slide-out-to-bottom data-[state=open]:slide-in-from-bottom',
    'left' => 'inset-y-0 left-0 h-full w-3/4 border-r sm:max-w-sm',
    'right' => 'inset-y-0 right-0 h-full w-3/4 border-l sm:max-w-sm',
];

$transitions = [
    'top' => ['enter' => '-translate-y-full', 'leave' => '-translate-y-full'],
    'bottom' => ['enter' => 'translate-y-full', 'leave' => 'translate-y-full'],
    'left' => ['enter' => '-translate-x-full', 'leave' => '-translate-x-full'],
    'right' => ['enter' => 'translate-x-full', 'leave' => 'translate-x-full'],
];
@endphp

<template x-teleport="body">
    <div 
        x-show="open" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/80"
        x-on:click="open = false"
    ></div>
    <div 
        x-show="open"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="{{ $transitions[$side]['enter'] ?? $transitions['right']['enter'] }}"
        x-transition:enter-end="translate-x-0 translate-y-0"
        x-transition:leave="transform transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0 translate-y-0"
        x-transition:leave-end="{{ $transitions[$side]['leave'] ?? $transitions['right']['leave'] }}"
        {{ $attributes->merge(['class' => 'fixed z-50 gap-4 bg-background p-6 shadow-lg ' . ($sideClasses[$side] ?? $sideClasses['right'])]) }}
    >
        {{ $slot }}
        <button 
            x-on:click="open = false" 
            class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            <span class="sr-only">Close</span>
        </button>
    </div>
</template>

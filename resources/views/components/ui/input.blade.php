@props([
    'type' => 'text',
    'disabled' => false,
])

<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'flex h-10 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all disabled:cursor-not-allowed disabled:opacity-50'
    ]) }}
    @if($disabled) disabled @endif
/>

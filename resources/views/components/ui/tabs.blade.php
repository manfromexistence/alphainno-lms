@props([
    'defaultValue' => '',
])

<div x-data="{ activeTab: '{{ $defaultValue }}' }" {{ $attributes }}>
    {{ $slot }}
</div>

@props([
    'error' => null,
])

@if($error || !$slot->isEmpty())
<p {{ $attributes->merge(['class' => 'text-sm font-medium text-destructive']) }}>
    {{ $error ?? $slot }}
</p>
@endif

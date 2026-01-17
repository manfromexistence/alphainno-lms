@props([
    'heading' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden p-1 text-foreground [&_[cmdk-group-heading]]:px-2 [&_[cmdk-group-heading]]:py-1.5 [&_[cmdk-group-heading]]:text-xs [&_[cmdk-group-heading]]:font-medium [&_[cmdk-group-heading]]:text-muted-foreground']) }}>
    @if($heading)
        <div cmdk-group-heading class="px-2 py-1.5 text-xs font-medium text-muted-foreground">{{ $heading }}</div>
    @endif
    {{ $slot }}
</div>

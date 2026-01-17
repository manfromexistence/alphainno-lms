@props([
    'type' => 'single',
    'collapsible' => true,
])

<div 
    x-data="{ 
        activeItems: [],
        type: '{{ $type }}',
        collapsible: {{ $collapsible ? 'true' : 'false' }},
        toggle(value) {
            if (this.type === 'single') {
                if (this.activeItems.includes(value) && this.collapsible) {
                    this.activeItems = [];
                } else {
                    this.activeItems = [value];
                }
            } else {
                if (this.activeItems.includes(value)) {
                    this.activeItems = this.activeItems.filter(item => item !== value);
                } else {
                    this.activeItems.push(value);
                }
            }
        },
        isOpen(value) {
            return this.activeItems.includes(value);
        }
    }"
    {{ $attributes->merge(['class' => 'w-full']) }}
>
    {{ $slot }}
</div>

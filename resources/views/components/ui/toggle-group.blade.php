@props([
    'type' => 'single',
    'variant' => 'default',
    'size' => 'default',
])

<div 
    x-data="{ 
        type: '{{ $type }}',
        selected: {{ $type === 'single' ? 'null' : '[]' }},
        toggle(value) {
            if (this.type === 'single') {
                this.selected = this.selected === value ? null : value;
            } else {
                if (this.selected.includes(value)) {
                    this.selected = this.selected.filter(v => v !== value);
                } else {
                    this.selected.push(value);
                }
            }
        },
        isSelected(value) {
            return this.type === 'single' ? this.selected === value : this.selected.includes(value);
        }
    }"
    {{ $attributes->merge(['class' => 'flex items-center justify-center gap-1']) }}
>
    {{ $slot }}
</div>

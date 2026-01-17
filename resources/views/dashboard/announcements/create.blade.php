@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Create Announcement</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <form action="{{ route('dashboard.announcements.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <x-ui.label for="title">Title</x-ui.label>
                    <x-ui.input type="text" name="title" id="title" required />
                </div>

                <div class="space-y-2">
                    <x-ui.label for="content">Content</x-ui.label>
                    <x-ui.textarea name="content" id="content" rows="6" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.select name="target_type" id="target_type" label="Target Audience" required>
                            <option value="all">Everyone</option>
                            <option value="batch">Specific Batch</option>
                            <option value="course">Specific Course</option>
                        </x-ui.select>
                    </div>
                    <div class="space-y-2" id="target_id_container" style="display: none;">
                        <x-ui.select name="target_id" id="target_id" label="Select Target">
                            <option value="">-- Select --</option>
                        </x-ui.select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <x-ui.select name="priority" label="Priority" required>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </x-ui.select>
                    </div>
                    <div class="space-y-2">
                        <x-ui.date-picker name="starts_at" id="starts_at" label="Start Date (Optional)" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.date-picker name="expires_at" id="expires_at" label="Expiry Date (Optional)" />
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" checked>
                    <label for="is_active" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Active immediately</label>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <x-ui.button type="button" variant="outline" as="a" href="{{ route('dashboard.announcements.index') }}">Cancel</x-ui.button>
                    <x-ui.button type="submit">Create Announcement</x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batches = @json($batches);
        const courses = @json($courses);
        const targetTypeSelect = document.getElementById('target_type');
        const targetIdContainer = document.getElementById('target_id_container'); // Container div
        
        // We need to target the native select inside the x-ui.select component
        // x-ui.select preserves the ID on the native select
        const targetIdSelect = document.getElementById('target_id'); 

        targetTypeSelect.addEventListener('change', function() {
            const type = this.value;
            
            // Clear options (keep first placeholder)
            // Since we are manipulating the DOM of a custom component, we need to be careful.
            // But if x-ui.select behaves as expected, we update the native options and then trigger a re-render if the component supports it, 
            // OR we might need to manually update the custom UI.
            
            // Re-reading Select.blade.php: 
            // It has `renderOptions(name)` exposed on window.
            // So we can update native options and call `renderOptions('target_id')`.
            
            targetIdSelect.innerHTML = '<option value="">-- Select --</option>';
            
            if (type === 'all') {
                targetIdContainer.style.display = 'none';
            } else {
                targetIdContainer.style.display = 'block';
                const data = type === 'batch' ? batches : courses;
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = item.name + (type === 'batch' && item.course ? ` (${item.course.name})` : '');
                    targetIdSelect.appendChild(option);
                });
            }

            // Trigger update of custom UI
            if (window.renderOptions) {
                window.renderOptions('target_id');
            }
        });
    });
</script>
@endpush
@endsection

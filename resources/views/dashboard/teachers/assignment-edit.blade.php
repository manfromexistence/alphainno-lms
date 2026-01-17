@extends('layouts.admin')

@section('title', 'Edit Teacher Assignments')
@section('page-title', 'Edit Assignments for ' . $teacher->user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <form action="{{ route('dashboard.teachers.assignment.update', $teacher) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Batch Assignments -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Batch Assignments</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($batches as $batch)
                            <label class="relative flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 {{ $teacher->batches->contains($batch->id) ? 'border-bd-green bg-emerald-50' : 'border-gray-200' }}">
                                <div class="min-w-0 flex-1 text-sm">
                                    <div class="font-medium text-gray-900">{{ $batch->name }}</div>
                                    <p class="text-gray-500">{{ $batch->course->title ?? 'No Course' }}</p>
                                </div>
                                <div class="ml-3 flex items-center h-5">
                                    <input type="checkbox" name="batch_ids[]" value="{{ $batch->id }}" 
                                        class="focus:ring-bd-green h-4 w-4 text-bd-green border-gray-300 rounded"
                                        {{ $teacher->batches->contains($batch->id) ? 'checked' : '' }}>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('batch_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Assignments -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject Assignments</h3>
                    <div id="subjects-container" class="space-y-3">
                        @if($teacher->subjects && is_array($teacher->subjects))
                            @foreach($teacher->subjects as $subject)
                                <div class="flex items-center space-x-2">
                                    <x-ui.input name="subjects[]" value="{{ $subject }}" placeholder="Enter subject name" />
                                    <button type="button" class="text-red-500 hover:text-red-700 remove-subject">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" id="add-subject" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bd-green">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Subject
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('dashboard.teachers.assignments') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark">Update Assignments</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('add-subject').addEventListener('click', function() {
        const container = document.getElementById('subjects-container');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';
        div.innerHTML = `
            <div class="flex-1">
                <input type="text" name="subjects[]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-bd-green focus:ring focus:ring-bd-green/20 focus:ring-opacity-50" placeholder="Enter subject name">
            </div>
            <button type="button" class="text-red-500 hover:text-red-700 remove-subject">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        `;
        container.appendChild(div);
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-subject')) {
            e.target.closest('.flex').remove();
        }
    });
</script>
@endpush
@endsection

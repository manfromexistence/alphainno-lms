@extends('layouts.admin')

@section('title', 'Create Batch')
@section('page-title', 'Create New Batch')
@section('page-description', 'Add a new batch for course scheduling')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('dashboard.batches.store') }}" method="POST">
            @csrf

            {{-- Global Validation Error Display --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <strong class="font-semibold">Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Batch Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.text-input name="name" label="Batch Name" required placeholder="Enter batch name" persist />
                    <x-ui.text-input name="code" label="Batch Code" required placeholder="e.g., WEB001-B1" persist />

                    <x-ui.select name="course_id" label="Course" required persist>
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="teacher_id" label="Assigned Teacher" persist>
                        <option value="">Select a teacher (optional)</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </div>

            <!-- Schedule & Room -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Schedule & Room</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.text-input name="schedule" label="Schedule" placeholder="e.g., Saturday-Sunday: 10:00 AM - 12:00 PM" persist />
                    <x-ui.text-input name="room" label="Room" placeholder="e.g., Room 101" persist />
                    <x-ui.date-picker name="start_date" label="Start Date" placeholder="Select start date" persist />
                    <x-ui.date-picker name="end_date" label="End Date" placeholder="Select end date" persist />
                </div>
            </div>

            <!-- Capacity & Status -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Capacity & Status</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.text-input name="max_students" label="Maximum Students" type="number" placeholder="e.g., 30" persist />
                    
                    <x-ui.select name="status" label="Status" required persist>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="completed">Completed</option>
                    </x-ui.select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-10">
                <a href="{{ route('dashboard.batches.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                    Create Batch
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                const keysToClear = ['input_persist_', 'image_persist_', 'date_persist_', 'select_persist_'];
                Object.keys(localStorage).forEach(key => {
                    if (keysToClear.some(prefix => key.startsWith(prefix + window.location.pathname))) {
                        localStorage.removeItem(key);
                    }
                });
            @endif
        });
    </script>
    @endpush
@endsection
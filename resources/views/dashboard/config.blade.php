@extends('layouts.admin')

@section('title', 'Dashboard Configuration')
@section('page-title', 'Dashboard Configuration')
@section('page-description', 'Configure dashboard widgets for different roles')

@section('content')
<form action="{{ route('dashboard.config') }}" method="POST">
    @csrf
    <div class="space-y-6">
        <!-- Student Widgets -->
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Student Dashboard Widgets</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach(['attendance_summary' => 'Attendance Summary', 'upcoming_exams' => 'Upcoming Exams', 'recent_results' => 'Recent Results', 'payment_status' => 'Payment Status', 'class_schedule' => 'Class Schedule'] as $key => $label)
                        <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="student_widgets[{{ $key }}]" value="1" {{ ($config['student_widgets'][$key] ?? false) ? 'checked' : '' }} class="rounded">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Teacher Widgets -->
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Teacher Dashboard Widgets</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach(['my_batches' => 'My Batches', 'today_schedule' => 'Today Schedule', 'pending_results' => 'Pending Results', 'attendance_entry' => 'Attendance Entry'] as $key => $label)
                        <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="teacher_widgets[{{ $key }}]" value="1" {{ ($config['teacher_widgets'][$key] ?? false) ? 'checked' : '' }} class="rounded">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.button type="submit">Save Configuration</x-ui.button>
    </div>
</form>
@endsection

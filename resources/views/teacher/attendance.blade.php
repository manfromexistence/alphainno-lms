@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Mark Attendance</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('teacher.attendance') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Batch</label>
                    <select name="batch_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                        <option value="">Select a batch</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ $selectedBatchId == $batch->id ? 'selected' : '' }}>
                                {{ $batch->name }} - {{ $batch->course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ $date }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Load Attendance
                    </button>
                </div>
            </div>
        </form>

        @if($selectedBatchId)
            @php
                $batch = \App\Models\Batch::with('students')->find($selectedBatchId);
            @endphp
            
            @if($batch && $batch->students->count() > 0)
                <form method="POST" action="{{ route('teacher.attendance.save') }}">
                    @csrf
                    <input type="hidden" name="batch_id" value="{{ $selectedBatchId }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($batch->students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->student_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" 
                                                        {{ isset($attendance[$student->id]) && $attendance[$student->id]->status === 'present' ? 'checked' : '' }}
                                                        class="form-radio text-green-600">
                                                    <span class="ml-2 text-sm text-gray-700">Present</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent"
                                                        {{ isset($attendance[$student->id]) && $attendance[$student->id]->status === 'absent' ? 'checked' : '' }}
                                                        class="form-radio text-red-600">
                                                    <span class="ml-2 text-sm text-gray-700">Absent</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="attendance[{{ $student->id }}]" value="late"
                                                        {{ isset($attendance[$student->id]) && $attendance[$student->id]->status === 'late' ? 'checked' : '' }}
                                                        class="form-radio text-yellow-600">
                                                    <span class="ml-2 text-sm text-gray-700">Late</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Save Attendance
                        </button>
                    </div>
                </form>
            @else
                <p class="text-gray-500 text-center py-8">No students found in this batch</p>
            @endif
        @else
            <p class="text-gray-500 text-center py-8">Please select a batch to mark attendance</p>
        @endif
    </div>
</div>
@endsection

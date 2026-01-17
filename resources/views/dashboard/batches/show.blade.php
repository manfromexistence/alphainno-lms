@extends('layouts.admin')

@section('title', 'Batch Details')
@section('page-title', 'Batch Details')
@section('page-description', 'Detailed information about the batch')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Batch Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-48 flex items-center">
                <div class="px-8 text-white">
                    <h1 class="text-3xl font-bold">{{ $batch->name }}</h1>
                    <p class="text-amber-100 mt-2">{{ $batch->code }} • {{ $batch->course->name ?? 'No course assigned' }}</p>
                </div>
            </div>

            <div class="px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $batch->students->count() }}</div>
                            <div class="text-sm text-gray-500">Enrolled Students</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $batch->max_students ?? '∞' }}</div>
                            <div class="text-sm text-gray-500">Max Capacity</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $batch->attendances->count() }}</div>
                            <div class="text-sm text-gray-500">Attendance Records</div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $batch->status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($batch->status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($batch->status) }}
                        </span>
                        <a href="{{ route('dashboard.batches.edit', $batch) }}"
                           class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Batch
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Enrolled Students -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Enrolled Students</h3>
                        <a href="{{ route('dashboard.students.batch-assignment') }}?batch_id={{ $batch->id }}"
                           class="inline-flex items-center px-3 py-1 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Students
                        </a>
                    </div>

                    @if($batch->students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($batch->students as $student)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if($student->user->profile_image)
                                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->user->profile_image }}" alt="{{ $student->user->name }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                                <span class="text-sm font-medium text-gray-700">{{ strtoupper(substr($student->user->name, 0, 1)) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $student->student_id ?? 'ID: N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->user->email }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->phone ?? 'N/A' }}</td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('dashboard.students.show', $student) }}" class="text-bd-green hover:text-bd-green-dark">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h4 class="text-gray-500 font-medium">No students enrolled</h4>
                            <p class="text-gray-400 text-sm mt-1">Students will appear here once they are assigned to this batch.</p>
                        </div>
                    @endif
                </div>

                <!-- Attendance Summary -->
                @if($batch->attendances->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Attendance</h3>
                    <div class="space-y-3">
                        @foreach($batch->attendances->take(5) as $attendance)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $attendance->date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $attendance->present_count ?? 0 }} present out of {{ $batch->students->count() }} students</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ number_format(($attendance->present_count ?? 0) / max($batch->students->count(), 1) * 100, 1) }}% Present
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Batch Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Batch Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="text-sm text-gray-900">
                                @if($batch->course)
                                    <a href="{{ route('dashboard.courses.show', $batch->course) }}" class="text-bd-green hover:text-bd-green-dark">
                                        {{ $batch->course->name }}
                                    </a>
                                @else
                                    No course assigned
                                @endif
                            </dd>
                        </div>
                        @if($batch->schedule)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Schedule</dt>
                            <dd class="text-sm text-gray-900">{{ $batch->schedule }}</dd>
                        </div>
                        @endif
                        @if($batch->room)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Room</dt>
                            <dd class="text-sm text-gray-900">{{ $batch->room }}</dd>
                        </div>
                        @endif
                        @if($batch->start_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="text-sm text-gray-900">{{ $batch->start_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        @if($batch->end_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="text-sm text-gray-900">{{ $batch->end_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        @if($batch->teachers->count() > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teacher</dt>
                            <dd class="text-sm text-gray-900">
                                @foreach($batch->teachers as $teacher)
                                    <div>{{ $teacher->user->name }}</div>
                                @endforeach
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="text-sm text-gray-900">{{ $batch->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('dashboard.courses.attendance') }}?batch_id={{ $batch->id }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Take Attendance
                        </a>

                        <a href="{{ route('dashboard.courses.materials') }}?batch_id={{ $batch->id }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Materials
                        </a>

                        <a href="{{ route('dashboard.courses.groups') }}?batch_id={{ $batch->id }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Manage Groups
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
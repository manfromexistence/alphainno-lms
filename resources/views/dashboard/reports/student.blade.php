@extends('layouts.admin')

@section('title', 'Student Reports')
@section('page-title', 'শিক্ষার্থী রিপোর্ট')
@section('page-description', 'View comprehensive student information with search, filters, and export options')

@section('content')
<div class="space-y-6">
    <!-- Summary Statistics - Requirement 8.5: Support searching and filtering students -->
    @if($stats)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">Total Students</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_students'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600">Active Students</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['active_students'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">With Outstanding Dues</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['students_with_dues'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-sm font-medium text-gray-600">Total Outstanding</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">৳{{ number_format($stats['total_outstanding'] ?? 0, 2) }}</p>
        </div>
    </div>
    @endif

    <!-- Filters Card - Requirement 8.5: Support searching and filtering students by name, batch, or enrollment status -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Search & Filter Students</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('dashboard.reports.student') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search by Name -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Student</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ $filters['name'] ?? '' }}"
                                placeholder="Search by name..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Batch Filter -->
                    <div class="space-y-2">
                        <x-ui.select name="batch_id" id="batch_id" label="Batch">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ ($filters['batch_id'] ?? '') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <!-- Enrollment Status Filter -->
                    <div class="space-y-2">
                        <x-ui.select name="enrollment_status" id="enrollment_status" label="Status">
                            <option value="">All Status</option>
                            @foreach($enrollmentStatuses as $status)
                                <option value="{{ $status }}" {{ ($filters['enrollment_status'] ?? '') === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <div class="space-y-2">
                        <x-ui.date-picker name="date_from" id="date_from" label="Enrolled From" value="{{ $filters['start_date'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.date-picker name="date_to" id="date_to" label="Enrolled To" value="{{ $filters['end_date'] ?? '' }}" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Search Students
                    </button>

                    @if(!empty(array_filter($filters)))
                        <a href="{{ route('dashboard.reports.student') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Report Data Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header with Export Buttons - Requirements 8.3, 8.4: Excel and PDF exports -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Student Report</h3>
                <p class="text-sm text-gray-500">{{ $students->count() }} students found</p>
            </div>
            
            @if($students->count() > 0)
            <div class="flex items-center gap-3">
                <!-- Excel Export Button - Requirement 8.3 -->
                <form action="{{ route('dashboard.reports.export-excel') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="report_type" value="student">
                    <input type="hidden" name="search" value="{{ $filters['name'] ?? '' }}">
                    <input type="hidden" name="batch_id" value="{{ $filters['batch_id'] ?? '' }}">
                    <input type="hidden" name="enrollment_status" value="{{ $filters['enrollment_status'] ?? '' }}">
                    <input type="hidden" name="date_from" value="{{ $filters['start_date'] ?? '' }}">
                    <input type="hidden" name="date_to" value="{{ $filters['end_date'] ?? '' }}">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </button>
                </form>

                <!-- PDF Export Button - Requirement 8.4 -->
                <form action="{{ route('dashboard.reports.export-pdf') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="report_type" value="student">
                    <input type="hidden" name="search" value="{{ $filters['name'] ?? '' }}">
                    <input type="hidden" name="batch_id" value="{{ $filters['batch_id'] ?? '' }}">
                    <input type="hidden" name="enrollment_status" value="{{ $filters['enrollment_status'] ?? '' }}">
                    <input type="hidden" name="date_from" value="{{ $filters['start_date'] ?? '' }}">
                    <input type="hidden" name="date_to" value="{{ $filters['end_date'] ?? '' }}">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Data Table - Requirement 8.2: Include enrollment date, batch, courses, payment history, and exam results -->
        <div class="overflow-x-auto">
            @if($students->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                            {{ strtoupper(substr($student['name'] ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $student['name'] ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $student['registration_no'] ?? '' }}</div>
                                            <div class="text-xs text-gray-400">{{ $student['email'] ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student['enrollment']['batch_name'] ?? 'N/A' }}</div>
                                    @if(!empty($student['enrollment']['course_name']))
                                        <div class="text-xs text-gray-500">{{ $student['enrollment']['course_name'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ !empty($student['enrollment']['enrollment_date']) ? date('M d, Y', strtotime($student['enrollment']['enrollment_date'])) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $student['enrollment']['batch_status'] ?? 'active';
                                        $statusClass = match($status) {
                                            'active' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'inactive' => 'bg-gray-100 text-gray-800',
                                            'suspended' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        ৳{{ number_format($student['payment_summary']['paid_amount'] ?? 0, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $student['payment_summary']['completed_payments'] ?? 0 }} payments
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $balance = $student['payment_summary']['due_amount'] ?? 0;
                                        $balanceClass = $balance > 0 ? 'text-red-600' : 'text-green-600';
                                    @endphp
                                    <div class="text-sm font-bold {{ $balanceClass }}">
                                        ৳{{ number_format(abs($balance), 2) }}
                                        @if($balance > 0)
                                            <span class="text-xs font-normal">(Due)</span>
                                        @elseif($balance < 0)
                                            <span class="text-xs font-normal">(Credit)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $avgScore = $student['performance_summary']['average_percentage'] ?? 0;
                                        $scoreClass = $avgScore >= 80 ? 'text-green-600' : ($avgScore >= 60 ? 'text-blue-600' : ($avgScore >= 40 ? 'text-amber-600' : 'text-red-600'));
                                    @endphp
                                    <div class="text-sm font-medium {{ $scoreClass }}">
                                        {{ number_format($avgScore, 1) }}%
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $student['performance_summary']['total_exams'] ?? 0 }} exams
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('dashboard.students.show', $student['id']) }}"
                                            class="text-gray-500 hover:text-blue-600 transition-colors" title="View Profile">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('dashboard.payments.history', $student['id']) }}"
                                            class="text-gray-500 hover:text-green-600 transition-colors" title="Payment History">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('dashboard.students.results', ['student' => $student['id']]) }}"
                                            class="text-gray-500 hover:text-purple-600 transition-colors" title="Exam Results">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                @if(method_exists($students, 'hasPages') && $students->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $students->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">No students found</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm">
                            @if(!empty(array_filter($filters)))
                                No students match your search criteria. Try adjusting your filters.
                            @else
                                Students will appear here once they are enrolled in the system.
                            @endif
                        </p>
                        @if(!empty(array_filter($filters)))
                            <a href="{{ route('dashboard.reports.student') }}"
                                class="mt-3 text-bd-green hover:text-bd-green-dark font-medium">
                                Clear all filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

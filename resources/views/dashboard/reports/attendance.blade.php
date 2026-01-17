@extends('layouts.admin')

@section('title', 'Attendance Reports')
@section('page-title', 'উপস্থিতি রিপোর্ট')
@section('page-description', 'Analyze student attendance data with filtering and export options')

@section('content')
<div class="space-y-6">
    <!-- Summary Statistics - Requirement 5.5: Calculate attendance percentages and statistics -->
    @if($report && isset($report['summary']))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600">Total Present</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['total_present'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600">Total Absent</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['total_absent'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">Total Late</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['total_late'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['attendance_rate'] ?? 0, 1) }}%</p>
        </div>
    </div>
    @endif

    <!-- Filters Card - Requirement 5.2: Support filtering by batch, date range, and individual student -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Filter Attendance Report</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('dashboard.reports.attendance') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

                    <!-- Status Filter -->
                    <div class="space-y-2">
                        <x-ui.select name="status" id="status" label="Status">
                            <option value="">All Status</option>
                            <option value="present" {{ ($filters['status'] ?? '') === 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ ($filters['status'] ?? '') === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ ($filters['status'] ?? '') === 'late' ? 'selected' : '' }}>Late</option>
                        </x-ui.select>
                    </div>

                    <div class="space-y-2">
                        <x-ui.date-picker name="date_from" id="date_from" label="From Date" value="{{ $filters['start_date'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.date-picker name="date_to" id="date_to" label="To Date" value="{{ $filters['end_date'] ?? '' }}" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Generate Report
                    </button>

                    @if(!empty(array_filter($filters)))
                        <a href="{{ route('dashboard.reports.attendance') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Report Data Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header with Export Buttons - Requirements 5.3, 5.4: Excel and PDF exports -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Attendance Report</h3>
                @if($report)
                    <p class="text-sm text-gray-500">{{ count($report['data'] ?? []) }} records found</p>
                @endif
            </div>
            
            @if($report && !empty($report['data']))
            <div class="flex items-center gap-3">
                <!-- Excel Export Button - Requirement 5.3 -->
                <form action="{{ route('dashboard.reports.export-excel') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="report_type" value="attendance">
                    <input type="hidden" name="batch_id" value="{{ $filters['batch_id'] ?? '' }}">
                    <input type="hidden" name="date_from" value="{{ $filters['start_date'] ?? '' }}">
                    <input type="hidden" name="date_to" value="{{ $filters['end_date'] ?? '' }}">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </button>
                </form>

                <!-- PDF Export Button - Requirement 5.4 -->
                <form action="{{ route('dashboard.reports.export-pdf') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="report_type" value="attendance">
                    <input type="hidden" name="batch_id" value="{{ $filters['batch_id'] ?? '' }}">
                    <input type="hidden" name="date_from" value="{{ $filters['start_date'] ?? '' }}">
                    <input type="hidden" name="date_to" value="{{ $filters['end_date'] ?? '' }}">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? '' }}">
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

        <!-- Data Table -->
        <div class="overflow-x-auto">
            @if($report && !empty($report['data']))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($report['data'] as $record)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ strtoupper(substr($record['student_name'] ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $record['student_name'] ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $record['registration_no'] ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record['batch_name'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ isset($record['date']) ? \Carbon\Carbon::parse($record['date'])->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = match($record['status'] ?? 'absent') {
                                            'present' => 'bg-green-100 text-green-800',
                                            'absent' => 'bg-red-100 text-red-800',
                                            'late' => 'bg-amber-100 text-amber-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($record['status'] ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record['check_in_time'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record['remarks'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">No attendance data found</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm">
                            @if(!empty(array_filter($filters)))
                                No attendance records match your filter criteria. Try adjusting your filters.
                            @else
                                Select a batch and date range to generate an attendance report.
                            @endif
                        </p>
                        @if(!empty(array_filter($filters)))
                            <a href="{{ route('dashboard.reports.attendance') }}"
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

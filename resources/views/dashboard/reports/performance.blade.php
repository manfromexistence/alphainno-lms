@extends('layouts.admin')

@section('title', 'Performance Reports')
@section('page-title', 'পারফরম্যান্স রিপোর্ট')
@section('page-description', 'Track and compare student academic performance with filtering and export options')

@section('content')
<div class="space-y-6">
    <!-- Summary Statistics - Requirement 7.5: Calculate averages, pass rates, and grade distributions -->
    @if($report && isset($report['summary']))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">Average Score</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['average_score'] ?? 0, 1) }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600">Pass Rate</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['pass_rate'] ?? 0, 1) }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">Highest Score</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['highest_score'] ?? 0, 1) }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-sm font-medium text-gray-600">Total Students</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($report['summary']['total_students'] ?? 0) }}</p>
        </div>
    </div>

    <!-- Grade Distribution -->
    @if(isset($report['summary']['grade_distribution']) && !empty($report['summary']['grade_distribution']))
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Grade Distribution</h3>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
            @foreach($report['summary']['grade_distribution'] as $grade => $count)
                @php
                    $gradeColor = match($grade) {
                        'A+', 'A' => 'bg-green-100 text-green-800 border-green-200',
                        'A-', 'B+' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'B', 'B-' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                        'C+', 'C' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'C-', 'D' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'F' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                    };
                @endphp
                <div class="text-center p-4 rounded-lg border {{ $gradeColor }}">
                    <p class="text-2xl font-bold">{{ $grade }}</p>
                    <p class="text-sm mt-1">{{ $count }} students</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <!-- Filters Card - Requirement 7.2: Support filtering by batch, course, and exam -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Filter Performance Report</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('dashboard.reports.performance') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Batch Filter -->
                    <div>
                        <label for="batch_id" class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
                        <select name="batch_id" id="batch_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ ($filters['batch_id'] ?? '') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Course Filter -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                        <select name="course_id" id="course_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exam Filter -->
                    <div>
                        <label for="exam_id" class="block text-sm font-medium text-gray-700 mb-1">Exam</label>
                        <select name="exam_id" id="exam_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">All Exams</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ ($filters['exam_id'] ?? '') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title ?? $exam->name ?? 'Exam #' . $exam->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Generate Button -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Generate Report
                        </button>
                    </div>
                </div>

                @if(!empty(array_filter($filters)))
                    <div class="flex items-center">
                        <a href="{{ route('dashboard.reports.performance') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Report Data Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header with Export Buttons -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Performance Report</h3>
                @if($report)
                    <p class="text-sm text-gray-500">{{ count($report['data'] ?? []) }} records found</p>
                @endif
            </div>
            
            @if($report && !empty($report['data']))
            <div class="flex items-center gap-3">
                <!-- Excel Export Button -->
                <form action="{{ route('dashboard.reports.export-excel') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="report_type" value="performance">
                    <input type="hidden" name="batch_id" value="{{ $filters['batch_id'] ?? '' }}">
                    <input type="hidden" name="course_id" value="{{ $filters['course_id'] ?? '' }}">
                    <input type="hidden" name="exam_id" value="{{ $filters['exam_id'] ?? '' }}">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </button>
                </form>

                <!-- PDF Export Button -->
                <form action="{{ route('dashboard.reports.export-pdf') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="report_type" value="performance">
                    <input type="hidden" name="batch_id" value="{{ $filters['batch_id'] ?? '' }}">
                    <input type="hidden" name="course_id" value="{{ $filters['course_id'] ?? '' }}">
                    <input type="hidden" name="exam_id" value="{{ $filters['exam_id'] ?? '' }}">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($report['data'] as $index => $record)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $rankClass = match($index) {
                                            0 => 'bg-yellow-100 text-yellow-800',
                                            1 => 'bg-gray-100 text-gray-800',
                                            2 => 'bg-orange-100 text-orange-800',
                                            default => 'bg-gray-50 text-gray-600'
                                        };
                                    @endphp
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $rankClass }} text-sm font-bold">
                                        {{ $record['rank'] ?? ($index + 1) }}
                                    </div>
                                </td>
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
                                    {{ $record['exam_name'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $record['obtained_marks'] ?? 0 }}/{{ $record['total_marks'] ?? 100 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($record['percentage'] ?? 0, 1) }}%</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $grade = $record['grade'] ?? 'N/A';
                                        $gradeClass = match($grade) {
                                            'A+', 'A' => 'bg-green-100 text-green-800',
                                            'A-', 'B+' => 'bg-blue-100 text-blue-800',
                                            'B', 'B-' => 'bg-cyan-100 text-cyan-800',
                                            'C+', 'C' => 'bg-amber-100 text-amber-800',
                                            'C-', 'D' => 'bg-orange-100 text-orange-800',
                                            'F' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gradeClass }}">
                                        {{ $grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $passed = ($record['percentage'] ?? 0) >= 40;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $passed ? 'Passed' : 'Failed' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">No performance data found</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm">
                            @if(!empty(array_filter($filters)))
                                No performance records match your filter criteria. Try adjusting your filters.
                            @else
                                Select a batch, course, or exam to generate a performance report.
                            @endif
                        </p>
                        @if(!empty(array_filter($filters)))
                            <a href="{{ route('dashboard.reports.performance') }}"
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

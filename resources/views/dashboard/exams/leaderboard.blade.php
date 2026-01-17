@extends('layouts.admin')

@section('title', 'Exam Leaderboard')
@section('page-title', 'পরীক্ষার লিডারবোর্ড')
@section('page-description', 'View top performers and merit lists')

@section('content')
    <div class="space-y-6">
        @if(!$examId)
            <!-- Exams List with Data Table -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Select Exam to View Leaderboard</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $exams->count() }} Exams
                    </span>
                </div>

                @php
                    $headers = [
                        ['key' => 'title', 'label' => 'Exam Title'],
                        ['key' => 'type', 'label' => 'Type'],
                        ['key' => 'batch', 'label' => 'Batch'],
                        ['key' => 'students', 'label' => 'Students'],
                        ['key' => 'avg_score', 'label' => 'Avg Score'],
                        ['key' => 'actions', 'label' => 'Actions'],
                    ];
                @endphp

                <x-ui.data-table id="exams-leaderboard-table" :headers="$headers" :rows="$exams" :route="route('dashboard.exams.leaderboard')">
                    @foreach($exams as $exam)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('dashboard.exams.leaderboard', ['exam_id' => $exam->id]) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $exam->title }}</div>
                                <div class="text-xs text-gray-500">{{ $exam->course->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($exam->type === 'mcq') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ strtoupper($exam->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->batch->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-semibold">{{ $exam->results_count }}</span> students
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($exam->results_count > 0)
                                    <span class="font-semibold">{{ number_format($exam->results->avg('obtained_marks'), 1) }}</span> / {{ $exam->total_marks }}
                                @else
                                    <span class="text-gray-400">No results</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                <a href="{{ route('dashboard.exams.leaderboard', ['exam_id' => $exam->id]) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    View Leaderboard
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </x-ui.data-table>
            </div>
        @else
            <!-- Back Button -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard.exams.leaderboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Exams List
                </a>
            </div>

        <!-- Exam Selection -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <form method="GET" action="{{ route('dashboard.exams.leaderboard') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Exam</label>
                        <select name="exam_id" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">Choose an exam to view leaderboard</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ $examId == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }} - {{ $exam->batch->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        @if($examId)
                            <a href="{{ route('dashboard.exams.show', $examId) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                View Exam Details
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($leaderboard->count() > 0)
            <!-- Top 3 Podium -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($leaderboard->take(3) as $index => $result)
                    <div class="bg-white rounded-xl shadow-md border-2 
                        @if($index === 0) border-yellow-400
                        @elseif($index === 1) border-gray-400
                        @else border-orange-400
                        @endif p-6 text-center">
                        
                        <!-- Rank Badge -->
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold
                                @if($index === 0) bg-yellow-100 text-yellow-600
                                @elseif($index === 1) bg-gray-100 text-gray-600
                                @else bg-orange-100 text-orange-600
                                @endif">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @else 🥉
                                @endif
                            </div>
                        </div>

                        <!-- Student Avatar -->
                        <div class="flex justify-center mb-3">
                            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-bold text-2xl">
                                    {{ substr($result->student->name_bn ?? $result->student->user->name ?? 'S', 0, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Student Info -->
                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                            {{ $result->student->user->name ?? $result->student->name_bn ?? 'N/A' }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            ID: {{ $result->student->registration_no ?? 'N/A' }}
                        </p>

                        <!-- Score -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-3">
                            <div class="text-3xl font-bold text-gray-900 mb-1">
                                {{ $result->obtained_marks }}/{{ $result->total_marks }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ number_format(($result->obtained_marks / $result->total_marks) * 100, 2) }}%
                            </div>
                        </div>

                        <!-- Grade -->
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                            @if($result->grade === 'A+') bg-green-100 text-green-800
                            @elseif($result->grade === 'A') bg-blue-100 text-blue-800
                            @else bg-indigo-100 text-indigo-800
                            @endif">
                            Grade: {{ $result->grade }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Full Leaderboard Table -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Complete Rankings</h3>
                    <div class="flex space-x-2">
                        <button onclick="exportLeaderboard()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </button>
                        <button onclick="sendResultsToAll()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Send SMS to All
                        </button>
                    </div>
                </div>

                <x-ui.data-table 
                    :headers="[
                        ['key' => 'rank', 'label' => 'Rank'],
                        ['key' => 'student', 'label' => 'Student'],
                        ['key' => 'obtained_marks', 'label' => 'Obtained'],
                        ['key' => 'total_marks', 'label' => 'Total'],
                        ['key' => 'percentage', 'label' => 'Percentage'],
                        ['key' => 'grade', 'label' => 'Grade'],
                        ['key' => 'status', 'label' => 'Status'],
                        ['key' => 'actions', 'label' => 'Actions'],
                    ]"
                    :rows="$leaderboard"
                    :searchable="true"
                    :sortable="true"
                    route="{{ route('dashboard.exams.leaderboard', ['exam_id' => $examId]) }}"
                >
                    @foreach($leaderboard as $index => $result)
                        <tr class="hover:bg-gray-50 {{ $index < 3 ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold 
                                        @if($index === 0) text-yellow-600
                                        @elseif($index === 1) text-gray-600
                                        @elseif($index === 2) text-orange-600
                                        @else text-gray-400
                                        @endif">
                                        #{{ $index + 1 }}
                                    </span>
                                    @if($index < 3)
                                        <span class="ml-2 text-xl">
                                            @if($index === 0) 🥇
                                            @elseif($index === 1) 🥈
                                            @else 🥉
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold text-sm">
                                                {{ substr($result->student->name_bn ?? $result->student->user->name ?? 'S', 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $result->student->user->name ?? $result->student->name_bn ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID: {{ $result->student->registration_no ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $result->obtained_marks }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->total_marks }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="font-semibold">{{ number_format(($result->obtained_marks / $result->total_marks) * 100, 2) }}%</span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-bd-green h-2 rounded-full" style="width: {{ ($result->obtained_marks / $result->total_marks) * 100 }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($result->grade === 'A+') bg-green-100 text-green-800
                                    @elseif($result->grade === 'A') bg-blue-100 text-blue-800
                                    @elseif($result->grade === 'B') bg-indigo-100 text-indigo-800
                                    @elseif($result->grade === 'C') bg-yellow-100 text-yellow-800
                                    @elseif($result->grade === 'D') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $result->grade }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($result->obtained_marks >= $result->exam->pass_marks)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        ✓ Passed
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        ✗ Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('dashboard.exams.view-result', [$result->exam, $result]) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-ui.data-table>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            {{ $leaderboard->count() }}
                        </div>
                        <div class="text-sm text-gray-500">Total Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">
                            {{ number_format($leaderboard->avg('obtained_marks'), 1) }}
                        </div>
                        <div class="text-sm text-gray-500">Average Score</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600 mb-2">
                            {{ $leaderboard->max('obtained_marks') }}
                        </div>
                        <div class="text-sm text-gray-500">Highest Score</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600 mb-2">
                            {{ $leaderboard->min('obtained_marks') }}
                        </div>
                        <div class="text-sm text-gray-500">Lowest Score</div>
                    </div>
                </div>
            </div>
        @else
            <!-- <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Results Yet</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">
                        @if($examId)
                            No students have completed this exam yet.
                        @else
                            Please select an exam to view the leaderboard.
                        @endif
                    </p>
                </div>
            </div> -->
        @endif
    </div>

    <script>
        function exportLeaderboard() {
            const examId = {{ $examId ?? 'null' }};
            if (examId) {
                window.location.href = `/dashboard/exams/${examId}/export-results?format=excel`;
            } else {
                alert('Please select an exam first');
            }
        }

        function sendResultsToAll() {
            const examId = {{ $examId ?? 'null' }};
            if (examId) {
                if (confirm('Send result SMS to all students?')) {
                    // Implement bulk SMS sending
                    alert('Bulk SMS feature will be implemented');
                }
            } else {
                alert('Please select an exam first');
            }
        }
    </script>
@endsection

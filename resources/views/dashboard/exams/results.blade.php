@extends('layouts.admin')

@section('title', 'Exam Results')
@section('page-title', 'পরীক্ষার ফলাফল')
@section('page-description', 'View and manage all exam results')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Results</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $results->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Passed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $results->filter(fn($r) => $r->obtained_marks >= $r->exam->pass_marks)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Failed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $results->filter(fn($r) => $r->obtained_marks < $r->exam->pass_marks)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Avg Score</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $results->count() > 0 ? number_format($results->avg('obtained_marks'), 1) : 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">All Exam Results</h3>
                <div class="flex space-x-2">
                    <button onclick="openSendSmsModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send SMS to Students
                    </button>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('dashboard.exams.results') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Exam</label>
                        <select name="exam_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">All Exams</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                            Filter Results
                        </button>
                    </div>
                </div>
            </form>

            <!-- Results Table -->
            @if($results->count() > 0)
                <x-ui.data-table 
                    :headers="[
                        ['key' => 'student', 'label' => 'Student'],
                        ['key' => 'exam', 'label' => 'Exam'],
                        ['key' => 'obtained_marks', 'label' => 'Obtained'],
                        ['key' => 'total_marks', 'label' => 'Total'],
                        ['key' => 'percentage', 'label' => 'Percentage'],
                        ['key' => 'grade', 'label' => 'Grade'],
                        ['key' => 'status', 'label' => 'Status'],
                        ['key' => 'cheating', 'label' => 'Cheating'],
                        ['key' => 'actions', 'label' => 'Actions'],
                    ]"
                    :rows="$results"
                    :searchable="true"
                    :sortable="true"
                    route="{{ route('dashboard.exams.results') }}"
                >
                    @foreach($results as $result)
                        <tr class="hover:bg-gray-50">
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
                                        @if($result->student->user->phone)
                                            <div class="text-xs text-gray-500">
                                                📱 {{ $result->student->user->phone }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $result->exam->title }}</div>
                                <div class="text-xs text-gray-500">{{ $result->exam->type }}</div>
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
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Passed
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $attempt = \App\Models\ExamAttempt::where('exam_id', $result->exam_id)
                                        ->where('student_id', $result->student_id)
                                        ->first();
                                @endphp
                                @if($attempt && $attempt->flagged_for_cheating)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800" title="Tab switches: {{ $attempt->tab_switches }}">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Flagged
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Clean
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
                                    <button onclick="sendSmsToStudent({{ $result->id }}, '{{ $result->student->user->phone ?? '' }}')" 
                                            class="text-green-600 hover:text-green-900" title="Send SMS">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-ui.data-table>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Results Found</h3>
                    <p class="text-gray-500">No exam results available yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Send SMS Modal -->
    <div id="sendSmsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Send Results via SMS</h3>
                    <button onclick="closeSendSmsModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('dashboard.communication.send-result') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Exam</label>
                        <select name="exam_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">Choose an exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Message Template</label>
                        <textarea name="message" rows="4" required placeholder="Dear {student_name}, Your result for {exam_title}: {marks}/{total_marks} ({grade}). Status: {status}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">Dear {student_name}, Your result for {exam_title}: {marks}/{total_marks} ({grade}). Status: {status}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Available variables: {student_name}, {exam_title}, {marks}, {total_marks}, {grade}, {status}</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeSendSmsModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Send SMS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openSendSmsModal() {
            document.getElementById('sendSmsModal').classList.remove('hidden');
        }

        function closeSendSmsModal() {
            document.getElementById('sendSmsModal').classList.add('hidden');
        }

        function sendSmsToStudent(resultId, phone) {
            if (!phone) {
                alert('Student phone number not available');
                return;
            }
            if (confirm('Send result SMS to ' + phone + '?')) {
                // Implement individual SMS sending
                alert('SMS sending feature will be implemented');
            }
        }
    </script>
@endsection

@extends('layouts.admin')

@section('title', 'View Result')
@section('page-title', 'পরীক্ষার ফলাফল দেখুন')
@section('page-description', 'View detailed exam result')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Result Details</h2>
                    <p class="text-gray-500 mt-1">{{ $exam->title }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('dashboard.exams.edit-result', [$exam, $result]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Result
                    </a>
                    <a href="{{ route('dashboard.exams.show', $exam) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Back to Exam
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-xl">
                                {{ substr($result->student->name_bn ?? $result->student->user->name ?? 'S', 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $result->student->user->name ?? $result->student->name_bn ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            ID: {{ $result->student->registration_no ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Batch: {{ $result->student->batch->name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Summary -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Result Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm text-blue-600 font-medium">Obtained Marks</div>
                    <div class="text-3xl font-bold text-blue-900 mt-1">{{ $result->obtained_marks }}</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-sm text-purple-600 font-medium">Total Marks</div>
                    <div class="text-3xl font-bold text-purple-900 mt-1">{{ $result->total_marks }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm text-green-600 font-medium">Percentage</div>
                    <div class="text-3xl font-bold text-green-900 mt-1">
                        {{ number_format(($result->obtained_marks / $result->total_marks) * 100, 2) }}%
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="text-sm text-yellow-600 font-medium">Grade</div>
                    <div class="text-3xl font-bold text-yellow-900 mt-1">{{ $result->grade }}</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Performance</span>
                    <span class="text-sm font-medium text-gray-700">
                        {{ $result->obtained_marks }} / {{ $result->total_marks }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-bd-green h-4 rounded-full transition-all duration-500" 
                         style="width: {{ ($result->obtained_marks / $result->total_marks) * 100 }}%">
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mt-6 flex items-center justify-center">
                @if($result->obtained_marks >= $exam->pass_marks)
                    <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 rounded-lg">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-semibold">PASSED</span>
                    </div>
                @else
                    <div class="inline-flex items-center px-6 py-3 bg-red-100 text-red-800 rounded-lg">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-semibold">FAILED</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Exam Information -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Exam Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Exam Type:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ strtoupper($exam->type) }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Pass Marks:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $exam->pass_marks }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Duration:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $exam->duration_minutes }} minutes</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Total Questions:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $exam->questions->count() }}</span>
                </div>
                @if($exam->start_time)
                <div>
                    <span class="text-sm font-medium text-gray-500">Start Time:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $exam->start_time->format('M d, Y h:i A') }}</span>
                </div>
                @endif
                @if($exam->end_time)
                <div>
                    <span class="text-sm font-medium text-gray-500">End Time:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $exam->end_time->format('M d, Y h:i A') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
            <div class="flex space-x-3">
                <a href="{{ route('dashboard.exams.edit-result', [$exam, $result]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Result
                </a>
                <form action="{{ route('dashboard.exams.delete-result', [$exam, $result]) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this result? This action cannot be undone.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Result
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

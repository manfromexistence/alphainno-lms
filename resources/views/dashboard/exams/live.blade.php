@extends('layouts.admin')

@section('title', 'Live Exams')
@section('page-title', 'লাইভ পরীক্ষা')
@section('page-description', 'Monitor and control ongoing live examinations')

@section('content')
    <div class="space-y-6">
        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Live Now</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $exams->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $exams->sum(fn($e) => $e->results->count()) }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Flagged</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Exams List -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                        <h2 class="text-lg font-semibold text-gray-900">Live Exams</h2>
                    </div>
                    <button onclick="location.reload()" class="inline-flex items-center px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="p-6">
                @if($exams->count() > 0)
                    <div class="space-y-4">
                        @foreach($exams as $exam)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $exam->title }}</h3>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($exam->type === 'mcq') bg-blue-100 text-blue-800
                                                @else bg-purple-100 text-purple-800
                                                @endif">
                                                {{ strtoupper($exam->type) }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                            <div>
                                                <p class="text-xs text-gray-500">Batch</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $exam->batch->name ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Course</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $exam->course->name ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Duration</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $exam->duration_minutes }} minutes</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Total Marks</p>
                                                <p class="text-sm font-medium text-gray-900">{{ $exam->total_marks }}</p>
                                            </div>
                                        </div>

                                        @if($exam->start_time && $exam->end_time)
                                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Started: {{ $exam->start_time->format('h:i A') }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Ends: {{ $exam->end_time->format('h:i A') }}
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Student Progress -->
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-700">Student Participation</span>
                                                <span class="text-sm font-semibold text-gray-900">{{ $exam->results->count() }} students</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-bd-green h-2 rounded-full" style="width: {{ $exam->results->count() > 0 ? 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ml-6 flex flex-col space-y-2">
                                        <a href="{{ route('dashboard.exams.show', $exam) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Monitor
                                        </a>
                                        <a href="{{ route('dashboard.exams.edit', $exam) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Live Exams</h3>
                        <p class="text-gray-500 max-w-sm mx-auto mb-4">There are no exams currently in progress. Check back later or create a new exam.</p>
                        <a href="{{ route('dashboard.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Exam
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('dashboard.exams.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">All Exams</p>
                        <p class="text-xs text-gray-500">View all exams</p>
                    </div>
                </a>

                <a href="{{ route('dashboard.exams.results') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Results</p>
                        <p class="text-xs text-gray-500">View exam results</p>
                    </div>
                </a>

                <a href="{{ route('dashboard.exams.leaderboard') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Leaderboard</p>
                        <p class="text-xs text-gray-500">Top performers</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
@endsection

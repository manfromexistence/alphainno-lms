@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Exams</h1>
            <p class="text-gray-600">View and take your exams</p>
        </div>

        <!-- Upcoming Exams -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-indigo-50">
                <h2 class="text-lg font-semibold text-indigo-900">Upcoming Exams</h2>
            </div>
            
            @if($upcomingExams->isEmpty())
            <div class="px-6 py-8 text-center text-gray-500">
                No upcoming exams scheduled.
            </div>
            @else
            <div class="divide-y divide-gray-200">
                @foreach($upcomingExams as $exam)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-12 h-12 {{ $exam->type === 'mcq' ? 'bg-blue-100' : 'bg-purple-100' }} rounded-lg flex items-center justify-center mr-4">
                            <span class="{{ $exam->type === 'mcq' ? 'text-blue-600' : 'text-purple-600' }} font-semibold text-sm">{{ strtoupper($exam->type) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $exam->name }}</p>
                            <p class="text-sm text-gray-500">{{ $exam->scheduled_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">{{ $exam->duration }} mins • {{ $exam->total_marks }} marks</p>
                        @if($exam->scheduled_at->isPast())
                        <a href="{{ $exam->type === 'mcq' ? route('student.exams.start', $exam) : route('student.exams.cq', $exam) }}" 
                           class="mt-2 inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            Start Exam
                        </a>
                        @else
                        <span class="text-sm text-gray-500">Starts {{ $exam->scheduled_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Past Exams / Results -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Past Exams</h2>
            </div>
            
            @if($pastExams->isEmpty())
            <div class="px-6 py-8 text-center text-gray-500">
                No completed exams yet.
            </div>
            @else
            <div class="divide-y divide-gray-200">
                @foreach($pastExams as $result)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-12 h-12 {{ $result->percentage >= 40 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center mr-4">
                            <span class="{{ $result->percentage >= 40 ? 'text-green-600' : 'text-red-600' }} font-bold text-lg">{{ $result->grade }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $result->exam?->name ?? 'Exam' }}</p>
                            <p class="text-sm text-gray-500">{{ $result->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-gray-900">{{ $result->marks }}/{{ $result->total_marks }}</p>
                        <p class="text-sm text-gray-500">{{ $result->percentage }}%</p>
                        <a href="{{ route('student.exam-result', $result) }}" class="text-sm text-indigo-600 hover:text-indigo-900">View Details →</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <div class="mt-6">
            <a href="{{ route('student.dashboard') }}" class="text-indigo-600 hover:text-indigo-500">← Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection

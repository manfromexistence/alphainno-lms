@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Batches</h1>
        <p class="text-gray-600">View all batches assigned to you</p>
    </div>

    @if($batches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($batches as $batch)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $batch->name }}</h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                {{ $batch->students->count() }} Students
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $batch->course->name }}
                            </div>
                            
                            @if($batch->start_date)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Started: {{ \Carbon\Carbon::parse($batch->start_date)->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('teacher.batches.students', $batch->id) }}" 
                               class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 text-center">
                                View Students
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No batches assigned</h3>
            <p class="mt-1 text-sm text-gray-500">You don't have any batches assigned to you yet.</p>
        </div>
    @endif
</div>
@endsection

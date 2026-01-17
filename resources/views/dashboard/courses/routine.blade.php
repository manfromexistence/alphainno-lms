@extends('layouts.admin')

@section('title', 'Course Routine')
@section('page-title', 'কোর্স রুটিন')
@section('page-description', 'Manage class schedules by course and batch')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Class Routine Management</h2>
                    <p class="text-sm text-gray-500 mt-1">Visualize and manage schedules across all active courses and batches</p>
                </div>
                <a href="{{ route('dashboard.batches.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Batch
                </a>
            </div>
        </div>

        <!-- Weekly Schedule View -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Weekly Class Schedule</h3>

            @if($batches->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($batches as $batch)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $batch->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $batch->course->name ?? 'No course' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                             {{ $batch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </div>

                            @if($batch->schedule)
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $batch->schedule }}
                                    </div>
                                    @if($batch->room)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $batch->room }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">No schedule set</p>
                            @endif

                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ $batch->students->count() }} students</span>
                                    <a href="{{ route('dashboard.batches.edit', $batch) }}"
                                       class="text-bd-green hover:text-bd-green-dark font-medium">
                                        Edit Schedule
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Batches Found</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mb-6">Create your first batch to start managing class schedules.</p>
                    <a href="{{ route('dashboard.batches.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create First Batch
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        @if($batches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Batches</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $batches->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Batches</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $batches->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $batches->sum(function($batch) { return $batch->students->count(); }) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Scheduled Classes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $batches->whereNotNull('schedule')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
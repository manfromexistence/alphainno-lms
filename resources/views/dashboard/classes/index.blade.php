@extends('layouts.admin')

@section('title', 'Class Management')
@section('page-title', 'Class Management')
@section('page-description', 'Manage classes 1-12 with their courses and batches')

@section('content')
    <div class="bg-white rounded-xl shadow-md">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">All Classes</h2>
        </div>

        <!-- Classes Grid -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($classes as $class)
                <a href="{{ route('dashboard.classes.show', $class['number']) }}" 
                   class="block bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-bd-green hover:shadow-lg transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-bd-green to-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md">
                            {{ $class['number'] }}
                        </div>
                        <span class="text-xs text-gray-500">Class {{ $class['number'] }}</span>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $class['name'] }}</h3>
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Courses:</span>
                            <span class="font-semibold text-bd-green">{{ $class['courses_count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Batches:</span>
                            <span class="font-semibold text-amber-600">{{ $class['batches_count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Students:</span>
                            <span class="font-semibold text-blue-600">{{ $class['students_count'] }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

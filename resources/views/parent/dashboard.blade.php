@extends('layouts.admin')

@section('title', 'Children Overview')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Children Overview</h2>
            <p class="text-sm text-muted-foreground">Monitor your children's academic progress and activities.</p>
        </div>
    </div>

    @if(isset($children) && $children->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($children as $childData)
                @php
                    $student = $childData['student'];
                    $attendanceRate = $childData['attendance_rate'];
                    $latestResult = $childData['latest_result'];
                    $pendingFees = $childData['pending_fees'];
                @endphp
                
                <x-ui.card class="hover:shadow-lg transition-shadow">
                    <x-ui.card-content class="pt-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                        <span class="text-white text-xl font-bold">{{ substr($student->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $student->student_id }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $student->batch?->course?->name ?? 'No Course' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <!-- Attendance -->
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Attendance</span>
                                </div>
                                <span class="text-sm font-bold {{ $attendanceRate >= 80 ? 'text-green-600' : ($attendanceRate >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $attendanceRate }}%
                                </span>
                            </div>

                            <!-- Latest Result -->
                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Latest Result</span>
                                </div>
                                @if($latestResult)
                                    <span class="text-sm font-bold text-purple-600">{{ $latestResult->percentage }}%</span>
                                @else
                                    <span class="text-sm text-gray-500">N/A</span>
                                @endif
                            </div>

                            <!-- Pending Fees -->
                            <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Pending Fees</span>
                                </div>
                                <span class="text-sm font-bold {{ $pendingFees > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    ৳{{ number_format($pendingFees, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex space-x-2">
                                <a href="{{ route('dashboard.children.progress') }}" class="flex-1 text-center px-3 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors text-sm font-medium">
                                    View Progress
                                </a>
                                <a href="{{ route('dashboard.children.attendance') }}" class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                    Attendance
                                </a>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            @endforeach
        </div>
    @else
        <x-ui.card>
            <x-ui.card-content class="pt-6">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No children linked</h3>
                    <p class="mt-1 text-sm text-gray-500">Contact the administrator to link your children's profiles.</p>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @endif
</div>
@endsection
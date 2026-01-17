@extends('layouts.admin')

@section('title', 'Class Schedule')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Class Schedule</h2>
            <p class="text-sm text-muted-foreground">{{ $student ? 'Your weekly timetable' : 'View all class schedules' }}</p>
        </div>
    </div>

    @if($schedules->isEmpty())
    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-6xl text-muted-foreground mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">No schedule available</h3>
                <p class="text-muted-foreground">Your class schedule will appear here when set.</p>
            </div>
        </x-ui.card-content>
    </x-ui.card>
    @else
    
    @php
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $scheduleByDay = $schedules->groupBy('day_of_week');
    @endphp
    
    <!-- Calendar View -->
    <x-ui.card>
        <x-ui.card-content class="p-0">
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                @foreach($days as $dayIndex => $day)
                <div class="bg-gray-50 p-2 text-center">
                    <span class="text-xs font-medium text-gray-500 uppercase">{{ substr($day, 0, 3) }}</span>
                </div>
                @endforeach
            </div>
            
            <div class="grid grid-cols-7 gap-px bg-gray-200 min-h-96">
                @foreach($days as $dayIndex => $day)
                <div class="bg-white p-2 min-h-32">
                    @if(isset($scheduleByDay[$dayIndex]))
                        @foreach($scheduleByDay[$dayIndex] as $schedule)
                        <div class="mb-2 p-2 bg-indigo-50 rounded-lg border-l-4 border-indigo-500">
                            <p class="text-xs font-semibold text-indigo-900">{{ $schedule->subject ?? $schedule->batch?->course?->name ?? 'Class' }}</p>
                            <p class="text-xs text-indigo-600">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                            </p>
                            @if($schedule->room)
                            <p class="text-xs text-gray-500 mt-1">Room: {{ $schedule->room }}</p>
                            @endif
                            @if($schedule->teacher_name || $schedule->teacher)
                            <p class="text-xs text-gray-500">{{ $schedule->teacher_name ?? $schedule->teacher?->name }}</p>
                            @endif
                            @if(!$student && $schedule->batch)
                            <p class="text-xs text-gray-400">Batch: {{ $schedule->batch->name }}</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-400 text-center mt-4">No class</p>
                    @endif
                </div>
                @endforeach
            </div>
        </x-ui.card-content>
    </x-ui.card>
    
    <!-- List View -->
    <x-ui.card>
        <x-ui.card-header>
            <h3 class="text-lg font-semibold">Schedule List</h3>
        </x-ui.card-header>
        <x-ui.card-content class="p-0">
            <div class="divide-y divide-gray-200">
                @foreach($schedules as $schedule)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                            <span class="text-indigo-600 font-semibold text-sm">{{ substr($days[$schedule->day_of_week] ?? 'N/A', 0, 3) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $schedule->subject ?? $schedule->batch?->course?->name ?? 'Class' }}</p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                            </p>
                            @if(!$student && $schedule->batch)
                            <p class="text-xs text-gray-400">Batch: {{ $schedule->batch->name }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($schedule->room)
                        <p class="text-sm text-gray-600">{{ $schedule->room }}</p>
                        @endif
                        @if($schedule->teacher_name || $schedule->teacher)
                        <p class="text-sm text-gray-500">{{ $schedule->teacher_name ?? $schedule->teacher?->name }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </x-ui.card-content>
    </x-ui.card>
    @endif
    
    <div class="mt-6">
        @if($student)
        <x-ui.button variant="ghost" as="a" href="{{ route('student.dashboard') }}">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </x-ui.button>
        @else
        <x-ui.button variant="ghost" as="a" href="{{ route('dashboard') }}">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </x-ui.button>
        @endif
    </div>
</div>
@endsection

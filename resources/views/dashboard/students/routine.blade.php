@extends('layouts.admin')

@section('title', 'Class Routine')
@section('page-title', 'ক্লাস রুটিন')
@section('page-description', 'Manage student class schedules and routines')

@section('content')
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Class Routine</h2>
            <form method="GET" action="{{ route('dashboard.students.routine') }}" class="w-64">
                <select name="batch_id" onchange="this.form.submit()" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-bd-green focus:border-transparent">
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $selectedBatchId == $batch->id ? 'selected' : '' }}>
                            {{ $batch->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border-b px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Day</th>
                        <th class="border-b px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            09:00 AM - 11:00 AM</th>
                        <th class="border-b px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            11:30 AM - 01:30 PM</th>
                        <th class="border-b px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            02:30 PM - 04:30 PM</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                        $timeSlots = [
                            ['start' => '09:00:00', 'end' => '11:00:00', 'color' => 'blue'],
                            ['start' => '11:30:00', 'end' => '13:30:00', 'color' => 'emerald'],
                            ['start' => '14:30:00', 'end' => '16:30:00', 'color' => 'orange'],
                        ];
                    @endphp
                    @foreach($days as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ ucfirst($day) }}
                            </td>
                            @foreach($timeSlots as $slot)
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @php
                                        $daySchedules = $schedules->get($day, collect());
                                        $classInSlot = $daySchedules->first(function($schedule) use ($slot) {
                                            $scheduleStart = \Carbon\Carbon::parse($schedule->start_time)->format('H:i:s');
                                            $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time)->format('H:i:s');
                                            $slotStart = $slot['start'];
                                            $slotEnd = $slot['end'];
                                            
                                            // Check if schedule overlaps with this time slot
                                            return ($scheduleStart >= $slotStart && $scheduleStart < $slotEnd) ||
                                                   ($scheduleEnd > $slotStart && $scheduleEnd <= $slotEnd) ||
                                                   ($scheduleStart <= $slotStart && $scheduleEnd >= $slotEnd);
                                        });
                                    @endphp
                                    
                                    @if($classInSlot)
                                        <div class="p-3 rounded-lg bg-{{ $slot['color'] }}-50 border border-{{ $slot['color'] }}-200">
                                            <div class="font-semibold text-{{ $slot['color'] }}-900 mb-1">
                                                {{ $classInSlot->subject }}
                                            </div>
                                            <div class="text-xs text-{{ $slot['color'] }}-700 space-y-1">
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($classInSlot->start_time)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($classInSlot->end_time)->format('h:i A') }}
                                                </div>
                                                @if($classInSlot->teacher)
                                                    <div class="flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $classInSlot->teacher->name ?? 'N/A' }}
                                                    </div>
                                                @endif
                                                @if($classInSlot->room)
                                                    <div class="flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        {{ $classInSlot->room }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 text-gray-400 italic text-center">
                                            No class scheduled
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($schedules->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-900">No schedules found</h3>
                <p class="mt-1 text-sm text-gray-500">Create class schedules to see them here.</p>
            </div>
        @endif
    </div>
@endsection
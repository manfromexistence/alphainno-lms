@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Class Schedule Management</h2>
        <x-ui.button as="a" href="{{ route('dashboard.schedules.create') }}">
            <i class="fas fa-plus mr-2"></i> Add Schedule Entry
        </x-ui.button>
    </div>

    @php
        $days = [
            0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
            4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($days as $dayNum => $dayName)
        <x-ui.card class="h-full">
            <x-ui.card-header class="bg-muted/50 pb-2">
                <x-ui.card-title class="text-lg">{{ $dayName }}</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="p-0">
                @php
                    $daySchedules = $schedules->where('day_of_week', $dayNum);
                @endphp
                
                @if($daySchedules->count() > 0)
                    <x-ui.table>
                        <x-ui.table-header>
                            <x-ui.table-row>
                                <x-ui.table-head class="w-[30%]">Time</x-ui.table-head>
                                <x-ui.table-head>Batch/Subject</x-ui.table-head>
                                <x-ui.table-head>Room</x-ui.table-head>
                                <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @foreach($daySchedules as $schedule)
                            <x-ui.table-row>
                                <x-ui.table-cell class="whitespace-nowrap font-medium text-xs">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <div class="font-bold text-sm">{{ $schedule->batch->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $schedule->subject ?? 'No Subject' }}</div>
                                    @if($schedule->teacher)
                                        <div class="text-xs text-primary mt-0.5">{{ $schedule->teacher->user->name }}</div>
                                    @endif
                                </x-ui.table-cell>
                                <x-ui.table-cell class="text-xs">{{ $schedule->room ?? '-' }}</x-ui.table-cell>
                                <x-ui.table-cell class="text-right">
                                    <div class="flex justify-end gap-1">
                                        <x-ui.button variant="ghost" size="icon" class="h-8 w-8" as="a" href="{{ route('dashboard.schedules.edit', $schedule) }}">
                                            <i class="fas fa-edit text-xs"></i>
                                        </x-ui.button>
                                        <form action="{{ route('dashboard.schedules.destroy', $schedule) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button variant="ghost" size="icon" type="submit" class="h-8 w-8 text-destructive hover:text-destructive hover:bg-destructive/10">
                                                <i class="fas fa-trash text-xs"></i>
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                            @endforeach
                        </x-ui.table-body>
                    </x-ui.table>
                @else
                    <div class="text-center py-6 text-muted-foreground text-sm">
                        No classes scheduled
                    </div>
                @endif
            </x-ui.card-content>
        </x-ui.card>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">System Activity Logs</h2>
            <p class="text-sm text-muted-foreground">View and audit system activities.</p>
        </div>
    </div>

    <!-- Filter -->
    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <form action="{{ route('dashboard.activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <x-ui.select name="user_id" label="User">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
                <div class="space-y-2">
                    <x-ui.select name="action" label="Action">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ ($filters['action'] ?? '') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
                <div class="space-y-2">
                    <x-ui.select name="model_type" label="Model Type">
                        <option value="">All Models</option>
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ ($filters['model_type'] ?? '') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
                <div class="flex items-end">
                    <x-ui.button type="submit" class="w-full">Filter Logs</x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Date</x-ui.table-head>
                    <x-ui.table-head>User</x-ui.table-head>
                    <x-ui.table-head>Action</x-ui.table-head>
                    <x-ui.table-head>Subject</x-ui.table-head>
                    <x-ui.table-head>Changes</x-ui.table-head>
                    <x-ui.table-head>IP Address</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($logs as $log)
                <x-ui.table-row>
                    <x-ui.table-cell class="whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $log->user->name ?? 'System' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge variant="secondary">{{ $log->action }}</x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <span class="text-xs text-muted-foreground">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</span>
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($log->changes)
                            <x-ui.accordion type="single" collapsible class="w-full">
                                <x-ui.accordion-item value="item-{{ $log->id }}" class="border-b-0">
                                    <x-ui.accordion-trigger class="py-1 text-xs">View Changes</x-ui.accordion-trigger>
                                    <x-ui.accordion-content>
                                        <pre class="bg-muted p-2 rounded text-[10px] overflow-auto max-w-[200px]">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                                    </x-ui.accordion-content>
                                </x-ui.accordion-item>
                            </x-ui.accordion>
                        @else
                            <span class="text-muted-foreground">-</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell class="text-xs">{{ $log->ip_address }}</x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-4 text-muted-foreground">
                        No activity logs found.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </x-ui.card>
</div>
@endsection

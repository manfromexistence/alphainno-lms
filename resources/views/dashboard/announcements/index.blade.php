@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold tracking-tight">Announcements</h2>
        <x-ui.button as="a" href="{{ route('dashboard.announcements.create') }}">
            <i class="fas fa-plus mr-2"></i> Create Announcement
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Title</x-ui.table-head>
                    <x-ui.table-head>Target</x-ui.table-head>
                    <x-ui.table-head>Priority</x-ui.table-head>
                    <x-ui.table-head>Starts At</x-ui.table-head>
                    <x-ui.table-head>Expires At</x-ui.table-head>
                    <x-ui.table-head>Status</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($announcements as $announcement)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $announcement->title }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $announcement->target_name }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <x-ui.badge style="background-color: {{ $announcement->priority_color }}; color: white;">
                            {{ ucfirst($announcement->priority) }}
                        </x-ui.badge>
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $announcement->starts_at ? $announcement->starts_at->format('M d, Y') : 'Immediately' }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y') : 'Never' }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($announcement->is_active)
                            <x-ui.badge class="bg-emerald-500 hover:bg-emerald-600">Active</x-ui.badge>
                        @else
                            <x-ui.badge variant="secondary">Inactive</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell class="text-right">
                        <div class="flex justify-end gap-2">
                            <x-ui.button variant="outline" size="sm" as="a" href="{{ route('dashboard.announcements.edit', $announcement) }}">
                                Edit
                            </x-ui.button>
                            <form action="{{ route('dashboard.announcements.destroy', $announcement) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <x-ui.button variant="destructive" size="sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="7" class="text-center py-8 text-muted-foreground">
                        No announcements found.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
        <div class="p-4 border-t">
            {{ $announcements->links() }}
        </div>
    </x-ui.card>
</div>
@endsection

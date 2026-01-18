@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Database Backups</h2>
            <p class="text-sm text-muted-foreground">Manage your system's database backups.</p>
        </div>
        <form action="{{ route('dashboard.backups.create') }}" method="POST">
            @csrf
            <x-ui.button type="submit">
                <i class="fas fa-save mr-2"></i> Create New Backup
            </x-ui.button>
        </form>
    </div>

    <x-ui.alert variant="warning">
        <i class="fas fa-exclamation-triangle h-4 w-4"></i>
        <x-ui.alert-title>Warning</x-ui.alert-title>
        <x-ui.alert-description>
            Restoring a backup will overwrite the current database. This action cannot be undone.
        </x-ui.alert-description>
    </x-ui.alert>

    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Filename</x-ui.table-head>
                    <x-ui.table-head>Size</x-ui.table-head>
                    <x-ui.table-head>Created At</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($backups as $backup)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $backup['filename'] }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ number_format($backup['size'] / 1024, 2) }} KB</x-ui.table-cell>
                    <x-ui.table-cell>{{ $backup['created_at']->format('M d, Y h:i A') }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-right">
                        <div class="flex justify-end gap-2">
                            <x-ui.button variant="outline" size="sm" as="a" href="{{ route('dashboard.backups.download', $backup['filename']) }}">
                                <i class="fas fa-download mr-1"></i> Download
                            </x-ui.button>
                            
                            <form action="{{ route('dashboard.backups.restore', $backup['filename']) }}" method="POST" class="inline-block" onsubmit="return showConfirmDialog({ title: 'Restore Database Backup', message: 'WARNING: This will overwrite your current database! Are you absolutely sure you want to restore this backup?', confirmText: 'Yes, Restore', type: 'danger', onConfirm: () => this.submit() }); return false;">
                                @csrf
                                <x-ui.button variant="warning" size="sm" type="submit">
                                    <i class="fas fa-undo mr-1"></i> Restore
                                </x-ui.button>
                            </form>

                            <form action="{{ route('dashboard.backups.destroy', $backup['filename']) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this backup file?')">
                                @csrf
                                @method('DELETE')
                                <x-ui.button variant="destructive" size="sm" type="submit">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="4" class="text-center py-4 text-muted-foreground">
                        No backups found.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card>
</div>
@endsection

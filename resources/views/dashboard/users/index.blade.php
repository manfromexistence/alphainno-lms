@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'User Management')
@section('page-description', 'Manage all users and their roles in the system')

@section('content')
    <x-ui.data-table 
        :headers="[
            ['key' => 'user', 'label' => 'User'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'joined', 'label' => 'Joined'],
            ['key' => 'actions', 'label' => 'Actions'],
        ]"
        :rows="$users"
        :searchable="true"
        :sortable="true"
        route="{{ route('dashboard.users.index') }}"
    >
        <x-slot name="actions">
            <x-ui.button as="a" href="{{ route('dashboard.users.create') }}">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add User
            </x-ui.button>
        </x-slot>

        @forelse($users as $user)
            <x-ui.table-row>
                <x-ui.table-cell>
                    <div class="flex items-center">
                        <div class="h-9 w-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold mr-3 text-xs">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="font-medium text-sm">{{ $user->name }}</div>
                    </div>
                </x-ui.table-cell>
                <x-ui.table-cell class="text-xs">{{ $user->email }}</x-ui.table-cell>
                <x-ui.table-cell>
                    @foreach ($user->roles as $role)
                        <x-ui.badge variant="{{ $role->slug === 'super-admin' ? 'destructive' : ($role->slug === 'teacher' ? 'default' : 'secondary') }}">
                            {{ $role->name }}
                        </x-ui.badge>
                    @endforeach
                </x-ui.table-cell>
                <x-ui.table-cell>
                    @if ($user->email_verified_at)
                        <x-ui.badge variant="outline" class="text-emerald-600 border-emerald-200">Verified</x-ui.badge>
                    @else
                        <x-ui.badge variant="outline" class="text-amber-600 border-amber-200">Pending</x-ui.badge>
                    @endif
                </x-ui.table-cell>
                <x-ui.table-cell class="text-xs text-muted-foreground">
                    {{ $user->created_at->format('M d, Y') }}
                </x-ui.table-cell>
                <x-ui.table-cell class="text-right">
                    <div class="flex justify-end gap-2">
                        <x-ui.button variant="ghost" size="icon" class="h-8 w-8" as="a" href="{{ route('dashboard.users.edit', $user) }}">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </x-ui.button>
                        <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST" class="inline"
                            onsubmit="return confirmDelete(this, 'Are you sure you want to delete this user? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <x-ui.button variant="ghost" size="icon" type="submit" class="h-8 w-8 text-destructive hover:bg-destructive/10">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </x-ui.button>
                        </form>
                    </div>
                </x-ui.table-cell>
            </x-ui.table-row>
        @empty
            <x-ui.table-row>
                <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                    No users found.
                </x-ui.table-cell>
            </x-ui.table-row>
        @endforelse
    </x-ui.data-table>
@endsection

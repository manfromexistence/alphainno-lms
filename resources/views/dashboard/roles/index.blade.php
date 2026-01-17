@extends('layouts.admin')

@section('title', 'Manage Roles')
@section('page-title', 'Role Management')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Roles List -->
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>System Roles</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="p-0">
                <div class="divide-y divide-border">
                    @forelse($roles as $role)
                        <div class="p-4 hover:bg-muted/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold flex items-center">
                                        <div class="h-2 w-2 rounded-full mr-2 
                                            {{ $role->slug === 'super-admin' ? 'bg-destructive' : 
                                               ($role->slug === 'teacher' ? 'bg-primary' : 'bg-secondary-foreground') }}">
                                        </div>
                                        {{ $role->name }}
                                    </h3>
                                    <p class="text-sm text-muted-foreground mt-1">{{ $role->description }}</p>
                                    <div class="mt-3 flex items-center space-x-4 text-xs text-muted-foreground">
                                        <span class="flex items-center">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $role->users->count() }} Users
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-key mr-1"></i>
                                            {{ $role->permissions->count() }} Permissions
                                        </span>
                                    </div>
                                </div>
                                <x-ui.button variant="ghost" size="sm" as="a" href="{{ route('dashboard.roles.show', $role) }}">
                                    Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </x-ui.button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-muted-foreground">
                            No roles found.
                        </div>
                    @endforelse
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Role Stats -->
        <div class="space-y-6">
            <!-- Role Distribution -->
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title class="text-base">User Distribution by Role</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="space-y-4">
                        @foreach($roles as $role)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-medium">{{ $role->name }}</span>
                                    <span class="text-xs text-muted-foreground">{{ $role->users->count() }} users</span>
                                </div>
                                <div class="w-full bg-secondary rounded-full h-2">
                                    @php
                                        $totalUsers = $roles->sum(fn($r) => $r->users->count());
                                        $percentage = $totalUsers > 0 ? ($role->users->count() / $totalUsers) * 100 : 0;
                                    @endphp
                                    <div class="h-2 rounded-full 
                                        {{ $role->slug === 'super-admin' ? 'bg-destructive' : 
                                           ($role->slug === 'teacher' ? 'bg-primary' : 'bg-slate-500') }}" 
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Permissions Overview -->
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title class="text-base">Permissions Overview</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $modules = $permissions->groupBy('module');
                        @endphp
                        @foreach($modules as $module => $perms)
                            <div class="p-3 bg-muted rounded-lg border border-border">
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">{{ $module ?? 'General' }}</h4>
                                <p class="text-2xl font-bold text-foreground">{{ $perms->count() }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
@endsection
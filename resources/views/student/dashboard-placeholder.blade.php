@extends('layouts.admin')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Student Dashboard</h2>
            <p class="text-sm text-muted-foreground">Overview of your academic progress and activities.</p>
        </div>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <div class="text-center py-12">
                <i class="fas fa-user-graduate text-6xl text-muted-foreground mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Student Portal - Dashboard</h3>
                <p class="text-muted-foreground">This is the student dashboard showing attendance, results, and activities. Access available after linking a student profile to your account.</p>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Class Schedule')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Class Schedule</h2>
            <p class="text-sm text-muted-foreground">View your class schedule and routine.</p>
        </div>
    </div>

    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-6xl text-muted-foreground mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Student Portal - Class Schedule</h3>
                <p class="text-muted-foreground">This page displays class schedules and routines for enrolled students. Access available after linking a student profile to your account.</p>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection

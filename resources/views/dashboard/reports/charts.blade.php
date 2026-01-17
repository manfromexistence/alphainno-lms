@extends('layouts.admin')

@section('title', 'Income/Expense Charts')
@section('page-title', 'আয়/ব্যয় চার্ট')
@section('page-description', 'View income and expense analytics charts')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-cyan-50 text-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Income/Expense Charts</h3>
            <p class="text-gray-500 max-w-sm mx-auto">This module is being prepared. You will soon be able to view
                interactive income and expense charts here.</p>
        </div>
    </div>
@endsection
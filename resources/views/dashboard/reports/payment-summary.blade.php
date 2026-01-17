@extends('layouts.admin')

@section('title', 'Payment Summary')
@section('page-title', 'পেমেন্ট সারসংক্ষেপ')
@section('page-description', 'View payment summary reports')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Payment Summary</h3>
            <p class="text-gray-500 max-w-sm mx-auto">This module is being prepared. You will soon be able to view
                detailed payment summary reports here.</p>
        </div>
    </div>
@endsection
@extends('layouts.admin')

@section('title', 'Export Financial Data')
@section('page-title', 'আর্থিক তথ্য রপ্তানি')
@section('page-description', 'Export financial data to Excel/PDF')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Export to Excel/PDF</h3>
            <p class="text-gray-500 max-w-sm mx-auto">This module is being prepared. You will soon be able to export
                financial data to Excel and PDF formats here.</p>
        </div>
    </div>
@endsection
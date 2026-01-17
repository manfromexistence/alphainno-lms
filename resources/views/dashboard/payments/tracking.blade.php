@extends('layouts.admin')

@section('title', 'Payment Tracking')
@section('page-title', 'Payment Tracking')
@section('page-description', 'Monitor student payment status and outstanding dues')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600">Total Outstanding</p>
            <p class="text-2xl font-bold text-red-600 mt-2">৳{{ number_format($totalDue ?? 0, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $studentsWithDues ?? 0 }} students with dues</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600">Total Advance</p>
            <p class="text-2xl font-bold text-green-600 mt-2">৳{{ number_format(abs($totalAdvance ?? 0), 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Students with advance payments</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">Total Students</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $students->total() ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">In current filter</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">Collection Rate</p>
            @php
                $totalFees = \App\Models\Student::sum('total_amount');
                $totalPaid = \App\Models\Student::sum('paid_amount');
                $collectionRate = $totalFees > 0 ? ($totalPaid / $totalFees) * 100 : 0;
            @endphp
            <p class="text-2xl font-bold text-amber-600 mt-2">{{ number_format($collectionRate, 1) }}%</p>
            <p class="text-xs text-gray-500 mt-1">Overall collection</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Student Payment Status</h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard.payments.notifications') }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium text-sm shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Send Reminders
                </a>
                <a href="{{ route('dashboard.payments.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Record Payment
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('dashboard.payments.tracking') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by student name or registration..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Batch Filter -->
                @if(isset($batches) && $batches->count() > 0)
                    <select name="batch_id"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <!-- Status Filter -->
                <select name="status"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="due" {{ request('status') === 'due' ? 'selected' : '' }}>Has Due</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Fully Paid</option>
                    <option value="advance" {{ request('status') === 'advance' ? 'selected' : '' }}>Has Advance</option>
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors shadow-sm">
                    Apply Filters
                </button>

                @if(request()->hasAny(['search', 'batch_id', 'status']))
                    <a href="{{ route('dashboard.payments.tracking') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Students Table -->
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Fee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            @php
                                $balance = $student->due_amount;
                                $percentage = $student->total_amount > 0 ? ($student->paid_amount / $student->total_amount) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Student -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->registration_no ?? 'No Reg' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Batch -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->batch->name ?? 'N/A' }}
                                </td>

                                <!-- Total Fee -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">৳{{ number_format($student->total_amount, 2) }}</span>
                                </td>

                                <!-- Paid -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <span class="text-sm font-medium text-green-600">৳{{ number_format($student->paid_amount, 2) }}</span>
                                        <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ number_format($percentage, 0) }}%</span>
                                    </div>
                                </td>

                                <!-- Balance -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold {{ $balance > 0 ? 'text-red-600' : ($balance < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                        @if($balance > 0)
                                            ৳{{ number_format($balance, 2) }} Due
                                        @elseif($balance < 0)
                                            ৳{{ number_format(abs($balance), 2) }} Advance
                                        @else
                                            Paid in Full
                                        @endif
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($balance > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Outstanding
                                        </span>
                                    @elseif($balance < 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Advance
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Cleared
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('dashboard.payments.history', $student) }}"
                                        class="text-blue-600 hover:text-blue-800 mr-3" title="Payment History">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </a>
                                    @if($balance > 0)
                                        <a href="{{ route('dashboard.payments.create') }}?student_id={{ $student->id }}"
                                            class="text-green-600 hover:text-green-800" title="Record Payment">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No students found</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'batch_id', 'status']))
                        No students match your filter criteria. Try adjusting your filters.
                    @else
                        No students have been enrolled yet.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'batch_id', 'status']))
                    <a href="{{ route('dashboard.payments.tracking') }}"
                        class="text-bd-green hover:text-bd-green-dark font-medium">
                        Clear all filters
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection

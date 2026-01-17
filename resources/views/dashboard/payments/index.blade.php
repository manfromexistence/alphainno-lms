@extends('layouts.admin')

@section('title', 'Manage Payments')
@section('page-title', 'Payment Management')
@section('page-description', 'Track and manage all payments in the system')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">৳{{ number_format($totalRevenue ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">This Month</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">৳{{ number_format($monthlyRevenue ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">৳{{ number_format($pendingAmount ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-sm font-medium text-gray-600">Total Transactions</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalTransactions ?? 0) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">All Payments</h2>
            <a href="{{ route('dashboard.payments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Record Payment
            </a>
        </div>

        <!-- Filters - Requirement 3.3: Support filtering by date range, payment method, batch, and student -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('dashboard.payments.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Search -->
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by student name or transaction ID..."
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

                    <!-- Status Filter -->
                    <div class="w-40">
                        <x-ui.select name="status">
                            <option value="">All Status</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </x-ui.select>
                    </div>

                    <!-- Payment Method Filter - Requirement 3.3 -->
                    <div class="w-48">
                        <x-ui.select name="payment_method">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ request('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </x-ui.select>
                    </div>

                    <!-- Batch Filter - Requirement 3.3 -->
                    @if(isset($batches) && $batches->count() > 0)
                    <div class="w-48">
                        <x-ui.select name="batch_id">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <!-- Date Range Filter - Requirement 3.3 -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">From:</span>
                        <x-ui.date-picker name="date_from" value="{{ request('date_from') }}" />
                        <span class="text-sm text-gray-500">To:</span>
                        <x-ui.date-picker name="date_to" value="{{ request('date_to') }}" />
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors shadow-sm">
                        Apply Filters
                    </button>

                    @if(request()->hasAny(['search', 'status', 'payment_method', 'batch_id', 'date_from', 'date_to']))
                        <a href="{{ route('dashboard.payments.index') }}"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @php
            $headers = [
                ['key' => 'receipt', 'label' => 'Receipt #'],
                ['key' => 'student', 'label' => 'Student'],
                ['key' => 'batch', 'label' => 'Batch'],
                ['key' => 'amount', 'label' => 'Amount'],
                ['key' => 'method', 'label' => 'Method'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'date', 'label' => 'Date'],
                ['key' => 'actions', 'label' => 'Actions'],
            ];
        @endphp

        <x-ui.data-table id="payments-table" :headers="$headers" :rows="$payments" :route="route('dashboard.payments.index')" :searchable="false">
            {{-- Searchable false because we have custom filters above --}}
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <!-- Receipt Number -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $payment->receipt_number ?? 'N/A' }}
                        </div>
                        @if($payment->transaction_id)
                            <div class="text-xs text-gray-500">TXN: {{ $payment->transaction_id }}</div>
                        @endif
                    </td>

                    <!-- Student -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ strtoupper(substr($payment->student->user->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $payment->student->user->name ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $payment->student->registration_no ?? '' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Batch -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->student->batch->name ?? 'N/A' }}
                    </td>

                    <!-- Amount -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">৳{{ number_format($payment->amount ?? 0, 2) }}</div>
                    </td>

                    <!-- Method -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                            @elseif($payment->payment_method === 'bkash') bg-pink-100 text-pink-800
                            @elseif($payment->payment_method === 'nagad') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}
                        </span>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if (($payment->status ?? 'completed') === 'completed') bg-green-100 text-green-800
                            @elseif(($payment->status ?? 'completed') === 'pending') bg-yellow-100 text-yellow-800
                            @elseif(($payment->status ?? 'completed') === 'refunded') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($payment->status ?? 'Completed') }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : $payment->created_at->format('M d, Y') }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                        <a href="{{ route('dashboard.payments.show', $payment) }}"
                            class="text-gray-500 hover:text-blue-600 transition-colors mr-3" title="View Details">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route('dashboard.payments.receipt', $payment) }}" target="_blank"
                            class="text-gray-500 hover:text-bd-green transition-colors mr-3" title="Print Receipt">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </a>
                        <a href="{{ route('dashboard.payments.history', $payment->student) }}"
                            class="text-gray-500 hover:text-purple-600 transition-colors" title="Payment History">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900">No payments found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->hasAny(['search', 'status', 'payment_method', 'batch_id', 'date_from', 'date_to']))
                                    No payments match your filter criteria. Try adjusting your filters.
                                @else
                                    Payments will appear here once students make payments.
                                @endif
                            </p>
                            @if(request()->hasAny(['search', 'status', 'payment_method', 'batch_id', 'date_from', 'date_to']))
                                <a href="{{ route('dashboard.payments.index') }}"
                                    class="mt-3 text-bd-green hover:text-bd-green-dark font-medium">
                                    Clear all filters
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.data-table>
    </div>
@endsection

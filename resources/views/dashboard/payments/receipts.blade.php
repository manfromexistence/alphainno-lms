@extends('layouts.admin')

@section('title', 'Payment Receipts')
@section('page-title', 'Payment Receipts')
@section('page-description', 'View and print official payment receipts')

@section('content')
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">All Receipts</h2>
            <a href="{{ route('dashboard.payments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Record Payment
            </a>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('dashboard.payments.receipts') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by receipt number or student name..."
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

                <!-- Date Range -->
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

                @if(request()->hasAny(['search', 'date_from', 'date_to']))
                    <a href="{{ route('dashboard.payments.receipts') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Receipts Table -->
        @if(isset($payments) && $payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $payment->receipt_number ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($payment->student->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $payment->student->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $payment->student->registration_no ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">৳{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                                        @elseif($payment->payment_method === 'bkash') bg-pink-100 text-pink-800
                                        @elseif($payment->payment_method === 'nagad') bg-orange-100 text-orange-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : $payment->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('dashboard.payments.show', $payment) }}"
                                        class="text-blue-600 hover:text-blue-800 mr-3" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('dashboard.payments.receipt', $payment) }}" target="_blank"
                                        class="text-green-600 hover:text-green-800" title="Print Receipt">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $payments->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">No receipts found</h3>
                    <p class="mt-1 text-sm text-gray-500 max-w-sm">
                        @if(request()->hasAny(['search', 'date_from', 'date_to']))
                            No receipts match your filter criteria. Try adjusting your filters or date range.
                        @else
                            Receipts will appear here once payments are recorded in the system.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'date_from', 'date_to']))
                        <a href="{{ route('dashboard.payments.receipts') }}"
                            class="mt-3 text-bd-green hover:text-bd-green-dark font-medium">
                            Clear all filters
                        </a>
                    @else
                        <a href="{{ route('dashboard.payments.create') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Record First Payment
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

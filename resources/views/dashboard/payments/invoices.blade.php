@extends('layouts.admin')

@section('title', 'Invoices')
@section('page-title', 'Invoice Management')
@section('page-description', 'Manage student fee invoices and billing')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">Total Invoices</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalInvoices ?? $invoices->total() ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600">Paid</p>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ number_format($paidCount ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-amber-600 mt-2">{{ number_format($pendingCount ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600">Overdue</p>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ number_format($overdueCount ?? 0) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">All Invoices</h2>
            <button type="button" onclick="openCreateInvoiceModal()"
                class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Invoice
            </button>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('dashboard.payments.invoices') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by invoice number or student name..."
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
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </x-ui.select>
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

                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('dashboard.payments.invoices') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Invoices Table -->
        @if(isset($invoices) && $invoices->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            @php
                                $isOverdue = $invoice->status === 'pending' && $invoice->due_date && $invoice->due_date->isPast();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</span>
                                    <div class="text-xs text-gray-500">{{ $invoice->created_at->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($invoice->student->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $invoice->student->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $invoice->student->registration_no ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">৳{{ number_format($invoice->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                        {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                                    </span>
                                    @if($isOverdue)
                                        <div class="text-xs text-red-500">{{ $invoice->due_date->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($invoice->status === 'paid') bg-green-100 text-green-800
                                        @elseif($isOverdue) bg-red-100 text-red-800
                                        @elseif($invoice->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($invoice->status === 'cancelled') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $isOverdue ? 'Overdue' : ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('dashboard.payments.invoice.show', $invoice) }}"
                                        class="text-blue-600 hover:text-blue-800 mr-3" title="View Invoice">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @if($invoice->status === 'pending')
                                        <a href="{{ route('dashboard.payments.create') }}?student_id={{ $invoice->student_id }}&invoice_id={{ $invoice->id }}"
                                            class="text-green-600 hover:text-green-800 mr-3" title="Record Payment">
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
            @if($invoices->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $invoices->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">No invoices found</h3>
                    <p class="mt-1 text-sm text-gray-500 max-w-sm">
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            No invoices match your filter criteria. Try adjusting your filters or date range.
                        @else
                            Create your first invoice to get started with billing.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('dashboard.payments.invoices') }}"
                            class="mt-3 text-bd-green hover:text-bd-green-dark font-medium">
                            Clear all filters
                        </a>
                    @else
                        <button type="button" onclick="openCreateInvoiceModal()"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create First Invoice
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Create Invoice Modal -->
    <div id="createInvoiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeCreateInvoiceModal()"></div>
            
            <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Create New Invoice</h3>
                    <button type="button" onclick="closeCreateInvoiceModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('dashboard.payments.invoices.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <x-ui.select name="student_id" id="modal_student_id" label="Student" required>
                                <option value="">-- Select Student --</option>
                                @if(isset($students))
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->user->name ?? 'N/A' }} - {{ $student->registration_no ?? 'No Reg' }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-ui.select>
                        </div>

                        <div>
                            <label for="modal_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">৳</span>
                                <input type="number" name="amount" id="modal_amount" step="0.01" min="0.01" required
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-ui.date-picker name="due_date" id="modal_due_date" label="Due Date" required
                                min="{{ date('Y-m-d') }}" />
                        </div>

                        <div>
                            <label for="modal_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" id="modal_description" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent"
                                placeholder="Invoice description (optional)"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateInvoiceModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-bd-green rounded-lg hover:bg-bd-green-dark transition-colors">
                            Create Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openCreateInvoiceModal() {
            document.getElementById('createInvoiceModal').classList.remove('hidden');
        }

        function closeCreateInvoiceModal() {
            document.getElementById('createInvoiceModal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateInvoiceModal();
            }
        });
    </script>
    @endpush
@endsection

@extends('layouts.admin')

@section('title', 'My Fees')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Payment History</h2>
            <p class="text-sm text-muted-foreground">{{ $student ? 'View your payment records and receipts' : 'View all payment records' }}</p>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-ui.card>
            <x-ui.card-content class="pt-6">
                <p class="text-sm text-gray-500">Total Fee</p>
                <p class="text-2xl font-bold text-gray-900">৳{{ number_format($summary['total_fee'], 2) }}</p>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-content class="pt-6">
                <p class="text-sm text-gray-500">Paid Amount</p>
                <p class="text-2xl font-bold text-green-600">৳{{ number_format($summary['paid_amount'], 2) }}</p>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-content class="pt-6">
                <p class="text-sm text-gray-500">Due Amount</p>
                <p class="text-2xl font-bold {{ $summary['due_amount'] > 0 ? 'text-red-600' : 'text-green-600' }}">৳{{ number_format($summary['due_amount'], 2) }}</p>
            </x-ui.card-content>
        </x-ui.card>
        <x-ui.card>
            <x-ui.card-content class="pt-6">
                <p class="text-sm text-gray-500">Payment Progress</p>
                <div class="flex items-center mt-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $summary['payment_percentage'] }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ $summary['payment_percentage'] }}%</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Payment History Table -->
    <x-ui.card>
        <x-ui.card-content class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(!$student)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            @if(!$student)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->student?->name ?? 'N/A' }}
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $payment->receipt_number ?? $payment->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ৳{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                {{ $payment->payment_method ?? 'Cash' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($payment->status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->status === 'completed' && $student)
                                <a href="{{ route('student.payments.receipt', $payment) }}" 
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-download mr-1"></i> Receipt
                                </a>
                                @elseif($payment->status === 'completed')
                                <span class="text-gray-400 text-xs">View Only</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $student ? 6 : 7 }}" class="px-6 py-12 text-center text-gray-500">
                                No payment records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
            @endif
        </x-ui.card-content>
    </x-ui.card>
    
    <div class="mt-6">
        @if($student)
        <x-ui.button variant="ghost" as="a" href="{{ route('student.dashboard') }}">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </x-ui.button>
        @else
        <x-ui.button variant="ghost" as="a" href="{{ route('dashboard') }}">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </x-ui.button>
        @endif
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <div class="md:col-span-4">
        <x-ui.card>
            <x-ui.card-content class="pt-6 text-center">
                <div class="mb-4">
                    <div class="h-20 w-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto text-primary text-2xl font-bold">
                        {{ substr($teacher->user->name, 0, 1) }}
                    </div>
                </div>
                <h3 class="text-xl font-bold">{{ $teacher->user->name }}</h3>
                <p class="text-muted-foreground mb-4">{{ $teacher->designation ?? 'Teacher' }}</p>
                <x-ui.separator class="my-4" />
                <h6 class="text-sm font-medium text-muted-foreground mb-1">Total Paid (All Time)</h6>
                <h3 class="text-2xl font-bold text-emerald-600">৳{{ number_format($totalPaid, 2) }}</h3>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <div class="md:col-span-8">
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title>Payment History</x-ui.card-title>
                <x-ui.button variant="secondary" size="sm" as="a" href="{{ route('dashboard.salaries.index') }}">
                    Back to List
                </x-ui.button>
            </x-ui.card-header>
            <x-ui.card-content>
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Date</x-ui.table-head>
                            <x-ui.table-head>Amount</x-ui.table-head>
                            <x-ui.table-head>Method</x-ui.table-head>
                            <x-ui.table-head>Notes</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse($salaries as $salary)
                        <x-ui.table-row>
                            <x-ui.table-cell>{{ $salary->payment_date->format('M d, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell class="font-bold">৳{{ number_format($salary->amount, 2) }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $salary->payment_method }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">{{ $salary->notes }}</x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-center py-8 text-muted-foreground">
                                No payment history found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
                <div class="mt-4">
                    {{ $salaries->links() }}
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection

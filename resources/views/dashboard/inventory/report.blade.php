@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Inventory Report</h2>
            <p class="text-sm text-muted-foreground">Overview of inventory status and value.</p>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-ui.card class="bg-primary text-primary-foreground">
            <x-ui.card-content class="pt-6 text-center">
                <h5 class="text-sm font-medium opacity-90">Total Items</h5>
                <h2 class="text-3xl font-bold mt-2">{{ $report['total_items'] }}</h2>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="bg-emerald-600 text-white">
            <x-ui.card-content class="pt-6 text-center">
                <h5 class="text-sm font-medium opacity-90">Total Inventory Value</h5>
                <h2 class="text-3xl font-bold mt-2">৳{{ number_format($report['total_value'], 2) }}</h2>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="bg-destructive text-destructive-foreground">
            <x-ui.card-content class="pt-6 text-center">
                <h5 class="text-sm font-medium opacity-90">Low Stock Items</h5>
                <h2 class="text-3xl font-bold mt-2">{{ $report['low_stock_count'] }}</h2>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Category Breakdown -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Value by Category</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Category</x-ui.table-head>
                        <x-ui.table-head>Item Count</x-ui.table-head>
                        <x-ui.table-head>Total Quantity</x-ui.table-head>
                        <x-ui.table-head>Total Value</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @foreach($report['by_category'] as $cat => $data)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $cat ?: 'Uncategorized' }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $data['count'] }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $data['total_quantity'] }}</x-ui.table-cell>
                        <x-ui.table-cell>৳{{ number_format($data['total_value'], 2) }}</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforeach
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Recent Transactions -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Recent Transactions</x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Date</x-ui.table-head>
                        <x-ui.table-head>Item</x-ui.table-head>
                        <x-ui.table-head>Type</x-ui.table-head>
                        <x-ui.table-head>Quantity</x-ui.table-head>
                        <x-ui.table-head>Amount</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @foreach($report['recent_transactions'] as $txn)
                    <x-ui.table-row>
                        <x-ui.table-cell class="text-sm text-muted-foreground">{{ $txn->created_at->format('M d, Y H:i') }}</x-ui.table-cell>
                        <x-ui.table-cell>
                            <a href="{{ route('dashboard.inventory.show', $txn->item) }}" class="font-medium hover:underline text-primary">
                                {{ $txn->item->name }}
                            </a>
                        </x-ui.table-cell>
                        <x-ui.table-cell>
                            @php
                                $variant = match($txn->type) {
                                    'purchase' => 'secondary', // We'll style these manually or assume variants exist. 
                                    'usage' => 'warning',
                                    default => 'outline'
                                };
                                $classes = match($txn->type) {
                                    'purchase' => 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200',
                                    'usage' => 'bg-amber-100 text-amber-800 hover:bg-amber-200',
                                    default => ''
                                };
                            @endphp
                            <x-ui.badge variant="outline" class="{{ $classes }}">
                                {{ ucfirst($txn->type) }}
                            </x-ui.badge>
                        </x-ui.table-cell>
                        <x-ui.table-cell>{{ $txn->quantity }}</x-ui.table-cell>
                        <x-ui.table-cell>৳{{ number_format($txn->total_amount, 2) }}</x-ui.table-cell>
                    </x-ui.table-row>
                    @endforeach
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endsection

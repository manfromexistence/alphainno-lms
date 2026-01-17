@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <!-- Item Details Card -->
    <div class="md:col-span-4 space-y-6">
        <x-ui.card class="{{ $item->isLowStock() ? 'border-destructive/50' : '' }}">
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title>Item Details</x-ui.card-title>
                @if($item->isLowStock())
                    <x-ui.badge variant="destructive">Low Stock</x-ui.badge>
                @endif
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">{{ $item->name }}</div>
                <p class="text-xs text-muted-foreground mb-4">{{ $item->category }}</p>

                <div class="space-y-4">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-sm font-medium">Current Stock</span>
                        <span class="font-bold">{{ $item->quantity }} {{ $item->unit }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-sm font-medium">Unit Price</span>
                        <span>৳{{ number_format($item->unit_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-sm font-medium">Total Value</span>
                        <span>৳{{ number_format($item->total_value, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-sm font-medium">Low Stock Threshold</span>
                        <span>{{ $item->min_stock_level }}</span>
                    </div>
                </div>

                @if($item->description)
                    <div class="mt-4 p-3 bg-muted rounded-md text-sm">
                        <span class="font-medium block mb-1">Description:</span>
                        {{ $item->description }}
                    </div>
                @endif
            </x-ui.card-content>
            <div class="p-6 pt-0 space-y-2">
                <!-- Purchase Modal -->
                <x-ui.dialog>
                    <x-ui.dialog-trigger as="x-ui.button" class="w-full bg-emerald-600 hover:bg-emerald-700">
                        Record Purchase (In)
                    </x-ui.dialog-trigger>
                    <x-ui.dialog-content>
                        <x-ui.dialog-header>
                            <x-ui.dialog-title>Record Purchase (Stock In)</x-ui.dialog-title>
                        </x-ui.dialog-header>
                        <form action="{{ route('dashboard.inventory.purchase', $item) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <x-ui.label>Quantity</x-ui.label>
                                <x-ui.input type="number" name="quantity" required min="1" />
                            </div>
                            <div class="space-y-2">
                                <x-ui.label>New Unit Price (Optional)</x-ui.label>
                                <x-ui.input type="number" name="unit_price" step="0.01" value="{{ $item->unit_price }}" />
                                <p class="text-[0.8rem] text-muted-foreground">Leave empty or same to keep current price.</p>
                            </div>
                            <div class="space-y-2">
                                <x-ui.label>Supplier</x-ui.label>
                                <x-ui.input type="text" name="supplier" />
                            </div>
                            <div class="space-y-2">
                                <x-ui.date-picker name="transaction_date" label="Date" value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="space-y-2">
                                <x-ui.label>Notes</x-ui.label>
                                <x-ui.textarea name="notes" rows="2" />
                            </div>
                            <x-ui.dialog-footer>
                                <x-ui.button type="submit" class="bg-emerald-600">Record Purchase</x-ui.button>
                            </x-ui.dialog-footer>
                        </form>
                    </x-ui.dialog-content>
                </x-ui.dialog>

                <!-- Usage Modal -->
                <x-ui.dialog>
                    <x-ui.dialog-trigger as="x-ui.button" variant="outline" class="w-full border-yellow-500 text-yellow-600 hover:bg-yellow-50">
                        Record Usage (Out)
                    </x-ui.dialog-trigger>
                    <x-ui.dialog-content>
                        <x-ui.dialog-header>
                            <x-ui.dialog-title>Record Usage (Stock Out)</x-ui.dialog-title>
                        </x-ui.dialog-header>
                        <form action="{{ route('dashboard.inventory.usage', $item) }}" method="POST" class="space-y-4">
                            @csrf
                            <x-ui.alert>
                                <x-ui.alert-title>Current Stock</x-ui.alert-title>
                                <x-ui.alert-description>{{ $item->quantity }} {{ $item->unit }}</x-ui.alert-description>
                            </x-ui.alert>
                            
                            <div class="space-y-2">
                                <x-ui.label>Quantity Used</x-ui.label>
                                <x-ui.input type="number" name="quantity" required min="1" max="{{ $item->quantity }}" />
                            </div>
                            <div class="space-y-2">
                                <x-ui.label>Purpose / Used For</x-ui.label>
                                <x-ui.input type="text" name="purpose" required />
                            </div>
                            <div class="space-y-2">
                                <x-ui.date-picker name="transaction_date" label="Date" value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="space-y-2">
                                <x-ui.label>Notes</x-ui.label>
                                <x-ui.textarea name="notes" rows="2" />
                            </div>
                            <x-ui.dialog-footer>
                                <x-ui.button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white">Record Usage</x-ui.button>
                            </x-ui.dialog-footer>
                        </form>
                    </x-ui.dialog-content>
                </x-ui.dialog>
            </div>
        </x-ui.card>
    </div>

    <!-- Transaction History -->
    <div class="md:col-span-8">
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Transaction History</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Date</x-ui.table-head>
                            <x-ui.table-head>Type</x-ui.table-head>
                            <x-ui.table-head>Qty</x-ui.table-head>
                            <x-ui.table-head>Unit Price</x-ui.table-head>
                            <x-ui.table-head>Total</x-ui.table-head>
                            <x-ui.table-head>Details</x-ui.table-head>
                            <x-ui.table-head>By</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse($history as $txn)
                        <x-ui.table-row>
                            <x-ui.table-cell class="whitespace-nowrap">{{ $txn->transaction_date->format('M d, Y') }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($txn->type === 'purchase')
                                    <x-ui.badge class="bg-emerald-500 hover:bg-emerald-600">Purchase</x-ui.badge>
                                @elseif($txn->type === 'usage')
                                    <x-ui.badge variant="secondary" class="bg-yellow-100 text-yellow-800">Usage</x-ui.badge>
                                @else
                                    <x-ui.badge variant="secondary">Adjustment</x-ui.badge>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>{{ $txn->quantity }}</x-ui.table-cell>
                            <x-ui.table-cell>৳{{ number_format($txn->unit_price, 2) }}</x-ui.table-cell>
                            <x-ui.table-cell>৳{{ number_format($txn->total_amount, 2) }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-xs text-muted-foreground max-w-[150px] truncate">
                                {{ $txn->supplier ?? $txn->purpose ?? $txn->notes ?? '-' }}
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-xs">{{ $txn->creator->name ?? 'System' }}</x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="7" class="text-center py-8 text-muted-foreground">
                                No transactions recorded yet.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection

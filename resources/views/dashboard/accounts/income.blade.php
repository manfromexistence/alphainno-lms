@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Income Management</h2>
            <p class="text-sm text-muted-foreground">Record and track incoming payments.</p>
        </div>
        
        <x-ui.dialog>
            <x-ui.dialog-trigger as="x-ui.button">
                <i class="fas fa-plus mr-2"></i> Record Income
            </x-ui.dialog-trigger>
            <x-ui.dialog-content class="sm:max-w-[500px]">
                <x-ui.dialog-header>
                    <x-ui.dialog-title>Record New Income</x-ui.dialog-title>
                </x-ui.dialog-header>
                <form action="{{ route('dashboard.accounts.income.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <x-ui.select name="category" label="Category" required>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </x-ui.select>
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="amount">Amount</x-ui.label>
                        <x-ui.input type="number" name="amount" id="amount" step="0.01" required />
                    </div>
                    <div class="space-y-2">
                        <x-ui.date-picker name="income_date" id="income_date" label="Date" value="{{ date('Y-m-d') }}" required />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="description">Description</x-ui.label>
                        <x-ui.textarea name="description" id="description" rows="3" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="reference">Reference / Receipt No.</x-ui.label>
                        <x-ui.input type="text" name="reference" id="reference" />
                    </div>
                    <x-ui.dialog-footer>
                        <x-ui.button type="submit">Save Income</x-ui.button>
                    </x-ui.dialog-footer>
                </form>
            </x-ui.dialog-content>
        </x-ui.dialog>
    </div>

    <!-- Filters -->
    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <form action="{{ route('dashboard.accounts.income') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <x-ui.select name="category" label="Category">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['category'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
                <div class="space-y-2">
                    <x-ui.date-picker name="from_date" label="From Date" value="{{ $filters['from_date'] ?? '' }}" />
                </div>
                <div class="space-y-2">
                    <x-ui.date-picker name="to_date" label="To Date" value="{{ $filters['to_date'] ?? '' }}" />
                </div>
                <div class="flex items-end">
                    <x-ui.button type="submit" variant="secondary" class="w-full">Filter Records</x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Income List -->
    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Date</x-ui.table-head>
                    <x-ui.table-head>Category</x-ui.table-head>
                    <x-ui.table-head>Description</x-ui.table-head>
                    <x-ui.table-head>Amount</x-ui.table-head>
                    <x-ui.table-head>Reference</x-ui.table-head>
                    <x-ui.table-head>Created By</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($incomes as $income)
                <x-ui.table-row>
                    <x-ui.table-cell>{{ $income->income_date->format('M d, Y') }}</x-ui.table-cell>
                    <x-ui.table-cell><x-ui.badge variant="secondary">{{ ucfirst($income->category) }}</x-ui.badge></x-ui.table-cell>
                    <x-ui.table-cell>{{ $income->description }}</x-ui.table-cell>
                    <x-ui.table-cell class="font-bold">৳{{ number_format($income->amount, 2) }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $income->reference ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-xs text-muted-foreground">{{ $income->creator->name ?? 'System' }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-right">
                        <form action="{{ route('dashboard.accounts.income.destroy', $income) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this income record?');">
                            @csrf
                            @method('DELETE')
                            <x-ui.button variant="ghost" size="icon" type="submit" class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                <i class="fas fa-trash"></i>
                            </x-ui.button>
                        </form>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="7" class="text-center py-8 text-muted-foreground">
                        No income records found.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
        <div class="p-4 border-t">
            {{ $incomes->links() }}
        </div>
    </x-ui.card>
</div>
@endsection
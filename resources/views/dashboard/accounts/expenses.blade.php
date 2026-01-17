@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Expense Management</h2>
            <p class="text-sm text-muted-foreground">Record and track company expenses.</p>
        </div>
        
        <x-ui.dialog>
            <x-ui.dialog-trigger as="x-ui.button" variant="destructive">
                <i class="fas fa-plus mr-2"></i> Record Expense
            </x-ui.dialog-trigger>
            <x-ui.dialog-content class="sm:max-w-[500px]">
                <x-ui.dialog-header>
                    <x-ui.dialog-title>Record New Expense</x-ui.dialog-title>
                </x-ui.dialog-header>
                <form action="{{ route('dashboard.accounts.expenses.store') }}" method="POST" class="space-y-4">
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
                        <x-ui.date-picker name="expense_date" id="expense_date" label="Date" value="{{ date('Y-m-d') }}" required />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="description">Description</x-ui.label>
                        <x-ui.textarea name="description" id="description" rows="3" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="receipt_number">Receipt Number</x-ui.label>
                        <x-ui.input type="text" name="receipt_number" id="receipt_number" />
                    </div>
                    <x-ui.dialog-footer>
                        <x-ui.button type="submit" variant="destructive">Save Expense</x-ui.button>
                    </x-ui.dialog-footer>
                </form>
            </x-ui.dialog-content>
        </x-ui.dialog>
    </div>

    <!-- Filters -->
    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <form action="{{ route('dashboard.accounts.expenses') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

    <!-- Expense List -->
    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Date</x-ui.table-head>
                    <x-ui.table-head>Category</x-ui.table-head>
                    <x-ui.table-head>Description</x-ui.table-head>
                    <x-ui.table-head>Amount</x-ui.table-head>
                    <x-ui.table-head>Receipt #</x-ui.table-head>
                    <x-ui.table-head>Created By</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($expenses as $expense)
                <x-ui.table-row>
                    <x-ui.table-cell>{{ $expense->expense_date->format('M d, Y') }}</x-ui.table-cell>
                    <x-ui.table-cell><x-ui.badge variant="outline" class="border-orange-200 text-orange-700 bg-orange-50">{{ ucfirst($expense->category) }}</x-ui.badge></x-ui.table-cell>
                    <x-ui.table-cell>{{ $expense->description }}</x-ui.table-cell>
                    <x-ui.table-cell class="font-bold text-destructive">৳{{ number_format($expense->amount, 2) }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $expense->receipt_number ?? '-' }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-xs text-muted-foreground">{{ $expense->creator->name ?? 'System' }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-right">
                        <div class="flex justify-end items-center gap-2">
                            <!-- Edit Modal (Per Row) -->
                            <x-ui.dialog>
                                <x-ui.dialog-trigger as="x-ui.button" variant="ghost" size="icon">
                                    <i class="fas fa-edit"></i>
                                </x-ui.dialog-trigger>
                                <x-ui.dialog-content class="sm:max-w-[500px]">
                                    <x-ui.dialog-header>
                                        <x-ui.dialog-title>Edit Expense</x-ui.dialog-title>
                                    </x-ui.dialog-header>
                                    <form action="{{ route('dashboard.accounts.expenses.update', $expense) }}" method="POST" class="space-y-4 text-left">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-2">
                                            <x-ui.select name="category" label="Category" :selected="$expense->category" required>
                                                @foreach($categories as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </x-ui.select>
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.label>Amount</x-ui.label>
                                            <x-ui.input type="number" name="amount" step="0.01" value="{{ $expense->amount }}" required />
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.date-picker name="expense_date" label="Date" value="{{ $expense->expense_date->format('Y-m-d') }}" required />
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.label>Description</x-ui.label>
                                            <x-ui.textarea name="description" rows="3">{{ $expense->description }}</x-ui.textarea>
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.label>Receipt Number</x-ui.label>
                                            <x-ui.input type="text" name="receipt_number" value="{{ $expense->receipt_number }}" />
                                        </div>
                                        <x-ui.dialog-footer>
                                            <x-ui.button type="submit">Update Expense</x-ui.button>
                                        </x-ui.dialog-footer>
                                    </form>
                                </x-ui.dialog-content>
                            </x-ui.dialog>

                            <form action="{{ route('dashboard.accounts.expenses.destroy', $expense) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <x-ui.button variant="ghost" size="icon" type="submit" class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                    <i class="fas fa-trash"></i>
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="7" class="text-center py-8 text-muted-foreground">
                        No expense records found.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
         <div class="p-4 border-t">
            {{ $expenses->links() }}
        </div>
    </x-ui.card>
</div>
@endsection
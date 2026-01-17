@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Inventory Management</h2>
            <p class="text-sm text-muted-foreground">Manage your stock, assets, and items.</p>
        </div>
        
        <x-ui.dialog>
            <x-ui.dialog-trigger as="x-ui.button">
                <i class="fas fa-plus mr-2"></i> Add New Item
            </x-ui.dialog-trigger>
            <x-ui.dialog-content class="sm:max-w-[600px]">
                <x-ui.dialog-header>
                    <x-ui.dialog-title>Add New Inventory Item</x-ui.dialog-title>
                </x-ui.dialog-header>
                <form action="{{ route('dashboard.inventory.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <x-ui.label for="name">Item Name</x-ui.label>
                        <x-ui.input type="text" name="name" id="name" required />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="category">Category</x-ui.label>
                        <x-ui.input type="text" name="category" id="category" list="categoriesList" required />
                        <datalist id="categoriesList">
                            @foreach($categories as $category)
                                <option value="{{ $category }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <x-ui.label for="quantity">Initial Quantity</x-ui.label>
                            <x-ui.input type="number" name="quantity" id="quantity" min="0" value="0" required />
                        </div>
                        <div class="space-y-2">
                            <x-ui.label for="unit">Unit (e.g., pcs, kg)</x-ui.label>
                            <x-ui.input type="text" name="unit" id="unit" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <x-ui.label for="unit_price">Unit Price</x-ui.label>
                            <x-ui.input type="number" name="unit_price" id="unit_price" step="0.01" min="0" required />
                        </div>
                        <div class="space-y-2">
                            <x-ui.label for="min_stock_level">Min Stock Level</x-ui.label>
                            <x-ui.input type="number" name="min_stock_level" id="min_stock_level" min="0" value="10" required />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="description">Description</x-ui.label>
                        <x-ui.textarea name="description" id="description" rows="2" />
                    </div>
                    <x-ui.dialog-footer>
                        <x-ui.button type="submit">Save Item</x-ui.button>
                    </x-ui.dialog-footer>
                </form>
            </x-ui.dialog-content>
        </x-ui.dialog>
    </div>

    <!-- Filters -->
    <x-ui.card>
        <x-ui.card-content class="pt-6">
            <form action="{{ route('dashboard.inventory.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <x-ui.label>Search</x-ui.label>
                    <x-ui.input type="text" name="search" placeholder="Search items..." value="{{ request('search') }}" />
                </div>
                <div class="space-y-2">
                    <x-ui.select name="category" label="Category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
                <div class="space-y-2">
                    <x-ui.select name="status" label="Status">
                        <option value="">All Status</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </x-ui.select>
                </div>
                <div class="flex items-end">
                    <x-ui.button type="submit" variant="secondary" class="w-full">Filter Inventory</x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Item Name</x-ui.table-head>
                    <x-ui.table-head>Category</x-ui.table-head>
                    <x-ui.table-head>Stock</x-ui.table-head>
                    <x-ui.table-head>Unit Price</x-ui.table-head>
                    <x-ui.table-head>Total Value</x-ui.table-head>
                    <x-ui.table-head>Last Updated</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($items as $item)
                <x-ui.table-row class="{{ $item->isLowStock() ? 'bg-red-50 hover:bg-red-100' : '' }}">
                    <x-ui.table-cell>
                        <div class="font-medium">{{ $item->name }}</div>
                        @if($item->isLowStock())
                            <x-ui.badge variant="destructive" class="mt-1 text-xs">Low Stock</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ $item->category }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        {{ $item->quantity }} <span class="text-muted-foreground text-xs">{{ $item->unit }}</span>
                    </x-ui.table-cell>
                    <x-ui.table-cell>৳{{ number_format($item->unit_price, 2) }}</x-ui.table-cell>
                    <x-ui.table-cell>৳{{ number_format($item->total_value, 2) }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $item->updated_at->format('M d, Y') }}</x-ui.table-cell>
                    <x-ui.table-cell class="text-right">
                        <div class="flex justify-end gap-2 items-center">
                            <x-ui.button variant="outline" size="sm" as="a" href="{{ route('dashboard.inventory.show', $item) }}">
                                <i class="fas fa-eye"></i>
                            </x-ui.button>
                            
                            <!-- Edit Modal (Per Row) -->
                            <x-ui.dialog>
                                <x-ui.dialog-trigger as="x-ui.button" variant="ghost" size="icon">
                                    <i class="fas fa-edit"></i>
                                </x-ui.dialog-trigger>
                                <x-ui.dialog-content class="sm:max-w-[600px]">
                                    <x-ui.dialog-header>
                                        <x-ui.dialog-title>Edit Item</x-ui.dialog-title>
                                    </x-ui.dialog-header>
                                    <form action="{{ route('dashboard.inventory.update', $item) }}" method="POST" class="space-y-4 text-left">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-2">
                                            <x-ui.label>Item Name</x-ui.label>
                                            <x-ui.input type="text" name="name" value="{{ $item->name }}" required />
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.label>Category</x-ui.label>
                                            <x-ui.input type="text" name="category" value="{{ $item->category }}" list="categoriesList" />
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <x-ui.label>Unit (e.g., pcs, kg)</x-ui.label>
                                                <x-ui.input type="text" name="unit" value="{{ $item->unit }}" required />
                                            </div>
                                            <div class="space-y-2">
                                                <x-ui.label>Unit Price</x-ui.label>
                                                <x-ui.input type="number" name="unit_price" step="0.01" value="{{ $item->unit_price }}" required />
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.label>Min Stock Level (Alert)</x-ui.label>
                                            <x-ui.input type="number" name="min_stock_level" value="{{ $item->min_stock_level }}" required />
                                        </div>
                                        <div class="space-y-2">
                                            <x-ui.label>Description</x-ui.label>
                                            <x-ui.textarea name="description" rows="2">{{ $item->description }}</x-ui.textarea>
                                        </div>
                                        <x-ui.dialog-footer>
                                            <x-ui.button type="submit">Update Item</x-ui.button>
                                        </x-ui.dialog-footer>
                                    </form>
                                </x-ui.dialog-content>
                            </x-ui.dialog>

                            <form action="{{ route('dashboard.inventory.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this item?');">
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
                        No inventory items found.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
        <div class="p-4 border-t">
            {{ $items->links() }}
        </div>
    </x-ui.card>
</div>
@endsection

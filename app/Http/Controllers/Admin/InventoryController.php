<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(protected InventoryService $inventoryService)
    {}

    public function index(Request $request)
    {
        $items = $this->inventoryService->getItems($request->all());
        $lowStockItems = $this->inventoryService->getLowStockItems();
        $categories = $this->inventoryService->getCategories();

        return view('dashboard.inventory.index', [
            'items' => $items,
            'lowStockItems' => $lowStockItems,
            'categories' => $categories,
            'filters' => $request->all(),
        ]);
    }

    public function create()
    {
        $categories = $this->inventoryService->getCategories();
        return view('dashboard.inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        $this->inventoryService->createItem($validated);

        return redirect()->route('dashboard.inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    public function show(InventoryItem $inventory)
    {
        $history = $this->inventoryService->getTransactionHistory($inventory);
        return view('dashboard.inventory.show', [
            'item' => $inventory,
            'history' => $history,
        ]);
    }

    public function edit(InventoryItem $inventory)
    {
        $categories = $this->inventoryService->getCategories();
        return view('dashboard.inventory.edit', [
            'item' => $inventory,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        $this->inventoryService->updateItem($inventory, $validated);

        return redirect()->route('dashboard.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $this->inventoryService->deleteItem($inventory);

        return redirect()->route('dashboard.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    public function purchase(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $this->inventoryService->recordPurchase($inventory, $validated);

        return redirect()->route('dashboard.inventory.show', $inventory)
            ->with('success', 'Purchase recorded successfully.');
    }

    public function usage(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
            'purpose' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->inventoryService->recordUsage($inventory, $validated);
            return redirect()->route('dashboard.inventory.show', $inventory)
                ->with('success', 'Usage recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function report()
    {
        $report = $this->inventoryService->getInventoryReport();
        return view('dashboard.inventory.report', compact('report'));
    }
}

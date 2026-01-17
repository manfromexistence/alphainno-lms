<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InventoryService
{
    public function createItem(array $data): InventoryItem
    {
        return InventoryItem::create($data);
    }

    public function updateItem(InventoryItem $item, array $data): InventoryItem
    {
        $item->update($data);
        return $item;
    }

    public function deleteItem(InventoryItem $item): bool
    {
        return $item->delete();
    }

    public function getItems(array $filters = []): LengthAwarePaginator
    {
        $query = InventoryItem::query()->orderBy('name');

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['low_stock'])) {
            $query->lowStock();
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function recordPurchase(InventoryItem $item, array $data): InventoryTransaction
    {
        return DB::transaction(function () use ($item, $data) {
            $transaction = InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'type' => 'purchase',
                'quantity' => $data['quantity'],
                'unit_price' => $data['unit_price'] ?? $item->unit_price,
                'total_amount' => ($data['unit_price'] ?? $item->unit_price) * $data['quantity'],
                'supplier' => $data['supplier'] ?? null,
                'transaction_date' => $data['transaction_date'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $item->increment('quantity', $data['quantity']);

            if (!empty($data['unit_price'])) {
                $item->update(['unit_price' => $data['unit_price']]);
            }

            return $transaction;
        });
    }

    public function recordUsage(InventoryItem $item, array $data): InventoryTransaction
    {
        return DB::transaction(function () use ($item, $data) {
            if ($item->quantity < $data['quantity']) {
                throw new \Exception('Insufficient stock available.');
            }

            $transaction = InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'type' => 'usage',
                'quantity' => $data['quantity'],
                'unit_price' => $item->unit_price,
                'total_amount' => $item->unit_price * $data['quantity'],
                'purpose' => $data['purpose'] ?? null,
                'transaction_date' => $data['transaction_date'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $item->decrement('quantity', $data['quantity']);

            return $transaction;
        });
    }

    public function recordAdjustment(InventoryItem $item, array $data): InventoryTransaction
    {
        return DB::transaction(function () use ($item, $data) {
            $oldQuantity = $item->quantity;
            $newQuantity = $data['new_quantity'];
            $difference = $newQuantity - $oldQuantity;

            $transaction = InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'type' => 'adjustment',
                'quantity' => abs($difference),
                'unit_price' => $item->unit_price,
                'total_amount' => 0,
                'notes' => ($data['notes'] ?? 'Stock adjustment') . " (Old: {$oldQuantity}, New: {$newQuantity})",
                'transaction_date' => now(),
                'created_by' => Auth::id(),
            ]);

            $item->update(['quantity' => $newQuantity]);

            return $transaction;
        });
    }

    public function getLowStockItems(int $threshold = null): Collection
    {
        $query = InventoryItem::query();
        
        if ($threshold !== null) {
            $query->where('quantity', '<', $threshold);
        } else {
            $query->lowStock();
        }

        return $query->orderBy('quantity')->get();
    }

    public function getTransactionHistory(InventoryItem $item, int $limit = 50): Collection
    {
        return $item->transactions()
            ->with('creator')
            ->orderBy('transaction_date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getInventoryReport(): array
    {
        $items = InventoryItem::all();
        
        $totalItems = $items->count();
        $totalValue = $items->sum('total_value');
        $lowStockCount = $items->filter(fn($item) => $item->isLowStock())->count();
        
        $byCategory = $items->groupBy('category')->map(function ($items) {
            return [
                'count' => $items->count(),
                'total_quantity' => $items->sum('quantity'),
                'total_value' => $items->sum('total_value'),
            ];
        });

        $recentTransactions = InventoryTransaction::with(['item', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'total_items' => $totalItems,
            'total_value' => $totalValue,
            'low_stock_count' => $lowStockCount,
            'by_category' => $byCategory,
            'recent_transactions' => $recentTransactions,
        ];
    }

    public function getCategories(): array
    {
        return InventoryItem::distinct('category')
            ->whereNotNull('category')
            ->pluck('category')
            ->toArray();
    }
}

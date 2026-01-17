<?php

use App\Models\InventoryItem;

test('inventory item model has correct fillable attributes', function () {
    $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'low_stock_threshold',
        'location',
    ];

    expect((new InventoryItem())->getFillable())->toBe($fillable);
});

test('inventory item model casts quantity to integer', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => '50',
    ]);

    expect($item->quantity)->toBeInt()
        ->and($item->quantity)->toBe(50);
});

test('inventory item model casts unit_price to decimal', function () {
    $item = InventoryItem::factory()->create([
        'unit_price' => 100,
    ]);

    expect($item->unit_price)->toBeString()
        ->and($item->unit_price)->toBe('100.00');
});

test('inventory item model casts low_stock_threshold to integer', function () {
    $item = InventoryItem::factory()->create([
        'low_stock_threshold' => '10',
    ]);

    expect($item->low_stock_threshold)->toBeInt()
        ->and($item->low_stock_threshold)->toBe(10);
});

test('inventory item isLowStock returns true when quantity is below threshold', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => 5,
        'low_stock_threshold' => 10,
    ]);

    expect($item->isLowStock())->toBeTrue();
});

test('inventory item isLowStock returns false when quantity is at or above threshold', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => 10,
        'low_stock_threshold' => 10,
    ]);

    expect($item->isLowStock())->toBeFalse();
});

test('inventory item getTotalValueAttribute calculates correctly', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => 10,
        'unit_price' => 25.50,
    ]);

    expect($item->total_value)->toBe(255.0);
});

test('inventory item has formatted unit price attribute', function () {
    $item = InventoryItem::factory()->create([
        'unit_price' => 1234.56,
    ]);

    expect($item->formatted_unit_price)->toBe('1,234.56');
});

test('inventory item has formatted total value attribute', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => 10,
        'unit_price' => 123.45,
    ]);

    expect($item->formatted_total_value)->toBe('1,234.50');
});

test('inventory item can be filtered by low stock', function () {
    InventoryItem::factory()->create([
        'name' => 'Low Stock Item',
        'quantity' => 5,
        'low_stock_threshold' => 10,
    ]);
    InventoryItem::factory()->create([
        'name' => 'Normal Stock Item',
        'quantity' => 20,
        'low_stock_threshold' => 10,
    ]);

    $lowStockItems = InventoryItem::lowStock()->get();

    expect($lowStockItems)->toHaveCount(1)
        ->and($lowStockItems->first()->name)->toBe('Low Stock Item');
});

test('inventory item can be filtered by category', function () {
    InventoryItem::factory()->create([
        'category' => 'furniture',
    ]);
    InventoryItem::factory()->create([
        'category' => 'electronics',
    ]);

    $furnitureItems = InventoryItem::withCategory('furniture')->get();

    expect($furnitureItems)->toHaveCount(1)
        ->and($furnitureItems->first()->category)->toBe('furniture');
});

test('inventory item total value is zero when quantity is zero', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => 0,
        'unit_price' => 100.00,
    ]);

    expect($item->total_value)->toBe(0.0);
});

test('inventory item total value is zero when unit price is zero', function () {
    $item = InventoryItem::factory()->create([
        'quantity' => 10,
        'unit_price' => 0.00,
    ]);

    expect($item->total_value)->toBe(0.0);
});

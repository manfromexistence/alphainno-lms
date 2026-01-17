<?php

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;

test('inventory transaction model has correct fillable attributes', function () {
    $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'unit_price',
        'total_amount',
        'supplier',
        'purpose',
        'transaction_date',
        'notes',
        'created_by',
    ];

    expect((new InventoryTransaction())->getFillable())->toBe($fillable);
});

test('inventory transaction model casts quantity to integer', function () {
    $transaction = InventoryTransaction::factory()->create([
        'quantity' => '25',
    ]);

    expect($transaction->quantity)->toBeInt()
        ->and($transaction->quantity)->toBe(25);
});

test('inventory transaction model casts unit_price to decimal', function () {
    $transaction = InventoryTransaction::factory()->create([
        'unit_price' => 150,
    ]);

    expect($transaction->unit_price)->toBeString()
        ->and($transaction->unit_price)->toBe('150.00');
});

test('inventory transaction model casts total_amount to decimal', function () {
    $transaction = InventoryTransaction::factory()->create([
        'total_amount' => 500,
    ]);

    expect($transaction->total_amount)->toBeString()
        ->and($transaction->total_amount)->toBe('500.00');
});

test('inventory transaction model casts transaction_date to date', function () {
    $transaction = InventoryTransaction::factory()->create([
        'transaction_date' => '2024-01-15',
    ]);

    expect($transaction->transaction_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('inventory transaction belongs to inventory item', function () {
    $item = InventoryItem::factory()->create();
    $transaction = InventoryTransaction::factory()->create([
        'inventory_item_id' => $item->id,
    ]);

    expect($transaction->item)->toBeInstanceOf(InventoryItem::class)
        ->and($transaction->item->id)->toBe($item->id);
});

test('inventory transaction belongs to creator user', function () {
    $user = User::factory()->create();
    $transaction = InventoryTransaction::factory()->create([
        'created_by' => $user->id,
    ]);

    expect($transaction->creator)->toBeInstanceOf(User::class)
        ->and($transaction->creator->id)->toBe($user->id);
});

test('inventory transaction has formatted unit price attribute', function () {
    $transaction = InventoryTransaction::factory()->create([
        'unit_price' => 1234.56,
    ]);

    expect($transaction->formatted_unit_price)->toBe('1,234.56');
});

test('inventory transaction has formatted total amount attribute', function () {
    $transaction = InventoryTransaction::factory()->create([
        'total_amount' => 5678.90,
    ]);

    expect($transaction->formatted_total_amount)->toBe('5,678.90');
});

test('inventory transaction can be filtered by type', function () {
    InventoryTransaction::factory()->purchase()->create();
    InventoryTransaction::factory()->usage()->create();
    InventoryTransaction::factory()->adjustment()->create();

    $purchases = InventoryTransaction::ofType('purchase')->get();
    $usages = InventoryTransaction::ofType('usage')->get();
    $adjustments = InventoryTransaction::ofType('adjustment')->get();

    expect($purchases)->toHaveCount(1)
        ->and($purchases->first()->type)->toBe('purchase')
        ->and($usages)->toHaveCount(1)
        ->and($usages->first()->type)->toBe('usage')
        ->and($adjustments)->toHaveCount(1)
        ->and($adjustments->first()->type)->toBe('adjustment');
});

test('inventory transaction can be filtered by purchases scope', function () {
    InventoryTransaction::factory()->purchase()->create();
    InventoryTransaction::factory()->usage()->create();

    $purchases = InventoryTransaction::purchases()->get();

    expect($purchases)->toHaveCount(1)
        ->and($purchases->first()->type)->toBe('purchase');
});

test('inventory transaction can be filtered by usages scope', function () {
    InventoryTransaction::factory()->purchase()->create();
    InventoryTransaction::factory()->usage()->create();

    $usages = InventoryTransaction::usages()->get();

    expect($usages)->toHaveCount(1)
        ->and($usages->first()->type)->toBe('usage');
});

test('inventory transaction can be filtered by adjustments scope', function () {
    InventoryTransaction::factory()->purchase()->create();
    InventoryTransaction::factory()->adjustment()->create();

    $adjustments = InventoryTransaction::adjustments()->get();

    expect($adjustments)->toHaveCount(1)
        ->and($adjustments->first()->type)->toBe('adjustment');
});

test('inventory transaction purchase type has supplier', function () {
    $transaction = InventoryTransaction::factory()->purchase()->create();

    expect($transaction->type)->toBe('purchase')
        ->and($transaction->supplier)->not->toBeNull()
        ->and($transaction->purpose)->toBeNull();
});

test('inventory transaction usage type has purpose', function () {
    $transaction = InventoryTransaction::factory()->usage()->create();

    expect($transaction->type)->toBe('usage')
        ->and($transaction->purpose)->not->toBeNull()
        ->and($transaction->supplier)->toBeNull();
});

test('inventory transaction adjustment type has purpose', function () {
    $transaction = InventoryTransaction::factory()->adjustment()->create();

    expect($transaction->type)->toBe('adjustment')
        ->and($transaction->purpose)->not->toBeNull()
        ->and($transaction->supplier)->toBeNull();
});

test('inventory item has many transactions relationship', function () {
    $item = InventoryItem::factory()->create();
    InventoryTransaction::factory()->count(3)->create([
        'inventory_item_id' => $item->id,
    ]);

    expect($item->transactions)->toHaveCount(3)
        ->and($item->transactions->first())->toBeInstanceOf(InventoryTransaction::class);
});

test('inventory transaction formatted unit price handles null value', function () {
    $transaction = InventoryTransaction::factory()->create([
        'unit_price' => null,
    ]);

    expect($transaction->formatted_unit_price)->toBe('0.00');
});

test('inventory transaction formatted total amount handles null value', function () {
    $transaction = InventoryTransaction::factory()->create([
        'total_amount' => null,
    ]);

    expect($transaction->formatted_total_amount)->toBe('0.00');
});

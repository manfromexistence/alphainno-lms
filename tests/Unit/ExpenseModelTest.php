<?php

use App\Models\Expense;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('expense model has correct fillable attributes', function () {
    $fillable = [
        'category',
        'amount',
        'description',
        'expense_date',
        'receipt_number',
        'notes',
        'created_by',
    ];

    expect((new Expense())->getFillable())->toBe($fillable);
});

test('expense model casts amount to decimal', function () {
    $expense = Expense::factory()->create([
        'amount' => 1000,
        'created_by' => $this->user->id,
    ]);

    expect($expense->amount)->toBeString()
        ->and($expense->amount)->toBe('1000.00');
});

test('expense model casts expense_date to date', function () {
    $expense = Expense::factory()->create([
        'expense_date' => '2024-01-15',
        'created_by' => $this->user->id,
    ]);

    expect($expense->expense_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('expense model has categories constant', function () {
    $expectedCategories = [
        'rent',
        'salary',
        'bills',
        'advertisement',
        'furniture',
        'paper',
        'stationary',
        'other',
    ];

    expect(Expense::CATEGORIES)->toBe($expectedCategories);
});

test('expense belongs to creator user', function () {
    $expense = Expense::factory()->create([
        'created_by' => $this->user->id,
    ]);

    expect($expense->creator)->toBeInstanceOf(User::class)
        ->and($expense->creator->id)->toBe($this->user->id);
});

test('expense has formatted amount attribute', function () {
    $expense = Expense::factory()->create([
        'amount' => 1234.56,
        'created_by' => $this->user->id,
    ]);

    expect($expense->formatted_amount)->toBe('1,234.56');
});

test('expense can be filtered by category', function () {
    Expense::factory()->create([
        'category' => 'rent',
        'created_by' => $this->user->id,
    ]);
    Expense::factory()->create([
        'category' => 'salary',
        'created_by' => $this->user->id,
    ]);

    $rentExpenses = Expense::withCategory('rent')->get();

    expect($rentExpenses)->toHaveCount(1)
        ->and($rentExpenses->first()->category)->toBe('rent');
});

test('expense can be filtered by date range', function () {
    Expense::factory()->create([
        'expense_date' => '2024-01-10',
        'created_by' => $this->user->id,
    ]);
    Expense::factory()->create([
        'expense_date' => '2024-01-20',
        'created_by' => $this->user->id,
    ]);
    Expense::factory()->create([
        'expense_date' => '2024-02-10',
        'created_by' => $this->user->id,
    ]);

    $januaryExpenses = Expense::betweenDates('2024-01-01', '2024-01-31')->get();

    expect($januaryExpenses)->toHaveCount(2);
});

test('expense can be filtered by specific date', function () {
    Expense::factory()->create([
        'expense_date' => '2024-01-15',
        'created_by' => $this->user->id,
    ]);
    Expense::factory()->create([
        'expense_date' => '2024-01-16',
        'created_by' => $this->user->id,
    ]);

    $expensesOnDate = Expense::onDate('2024-01-15')->get();

    expect($expensesOnDate)->toHaveCount(1)
        ->and($expensesOnDate->first()->expense_date->format('Y-m-d'))->toBe('2024-01-15');
});

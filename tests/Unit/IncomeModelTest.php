<?php

use App\Models\Income;
use App\Models\Student;
use App\Models\Payment;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('income model has correct fillable attributes', function () {
    $fillable = [
        'category',
        'amount',
        'description',
        'income_date',
        'student_id',
        'payment_id',
        'reference',
        'created_by',
    ];

    expect((new Income())->getFillable())->toBe($fillable);
});

test('income model casts amount to decimal', function () {
    $income = Income::factory()->create([
        'amount' => 5000,
        'created_by' => $this->user->id,
    ]);

    expect($income->amount)->toBeString()
        ->and($income->amount)->toBe('5000.00');
});

test('income model casts income_date to date', function () {
    $income = Income::factory()->create([
        'income_date' => '2024-01-15',
        'created_by' => $this->user->id,
    ]);

    expect($income->income_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('income model has categories constant', function () {
    $expectedCategories = [
        'admission',
        'tuition',
        'materials',
        'other',
    ];

    expect(Income::CATEGORIES)->toBe($expectedCategories);
});

test('income belongs to creator user', function () {
    $income = Income::factory()->create([
        'created_by' => $this->user->id,
    ]);

    expect($income->creator)->toBeInstanceOf(User::class)
        ->and($income->creator->id)->toBe($this->user->id);
});

test('income belongs to student when student_id is set', function () {
    // Create a user for the student
    $studentUser = User::factory()->create();
    
    $student = Student::create([
        'user_id' => $studentUser->id,
        'name_bn' => 'Test Student',
        'registration_no' => 'TEST-2024-001',
        'batch_id' => null,
        'total_amount' => 10000,
        'paid_amount' => 0,
        'due_amount' => 10000,
    ]);
    
    $income = Income::factory()->create([
        'student_id' => $student->id,
        'created_by' => $this->user->id,
    ]);

    expect($income->student)->toBeInstanceOf(Student::class)
        ->and($income->student->id)->toBe($student->id);
});

test('income belongs to payment when payment_id is set', function () {
    // Create a user for the student
    $studentUser = User::factory()->create();
    
    $student = Student::create([
        'user_id' => $studentUser->id,
        'name_bn' => 'Test Student',
        'registration_no' => 'TEST-2024-002',
        'batch_id' => null,
        'total_amount' => 10000,
        'paid_amount' => 0,
        'due_amount' => 10000,
    ]);
    
    $payment = Payment::create([
        'student_id' => $student->id,
        'amount' => 5000,
        'payment_method' => 'cash',
        'payment_date' => now(),
        'status' => 'completed',
    ]);
    
    $income = Income::factory()->create([
        'payment_id' => $payment->id,
        'created_by' => $this->user->id,
    ]);

    expect($income->payment)->toBeInstanceOf(Payment::class)
        ->and($income->payment->id)->toBe($payment->id);
});

test('income has formatted amount attribute', function () {
    $income = Income::factory()->create([
        'amount' => 12345.67,
        'created_by' => $this->user->id,
    ]);

    expect($income->formatted_amount)->toBe('12,345.67');
});

test('income can be filtered by category', function () {
    Income::factory()->create([
        'category' => 'admission',
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'category' => 'tuition',
        'created_by' => $this->user->id,
    ]);

    $admissionIncomes = Income::withCategory('admission')->get();

    expect($admissionIncomes)->toHaveCount(1)
        ->and($admissionIncomes->first()->category)->toBe('admission');
});

test('income can be filtered by date range', function () {
    Income::factory()->create([
        'income_date' => '2024-01-10',
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'income_date' => '2024-01-20',
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'income_date' => '2024-02-10',
        'created_by' => $this->user->id,
    ]);

    $januaryIncomes = Income::betweenDates('2024-01-01', '2024-01-31')->get();

    expect($januaryIncomes)->toHaveCount(2);
});

test('income can be filtered by specific date', function () {
    Income::factory()->create([
        'income_date' => '2024-01-15',
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'income_date' => '2024-01-16',
        'created_by' => $this->user->id,
    ]);

    $incomesOnDate = Income::onDate('2024-01-15')->get();

    expect($incomesOnDate)->toHaveCount(1)
        ->and($incomesOnDate->first()->income_date->format('Y-m-d'))->toBe('2024-01-15');
});

test('income can be filtered by student', function () {
    // Create users for the students
    $studentUser1 = User::factory()->create();
    $studentUser2 = User::factory()->create();
    
    $student1 = Student::create([
        'user_id' => $studentUser1->id,
        'name_bn' => 'Test Student 1',
        'registration_no' => 'TEST-2024-003',
        'batch_id' => null,
        'total_amount' => 10000,
        'paid_amount' => 0,
        'due_amount' => 10000,
    ]);
    
    $student2 = Student::create([
        'user_id' => $studentUser2->id,
        'name_bn' => 'Test Student 2',
        'registration_no' => 'TEST-2024-004',
        'batch_id' => null,
        'total_amount' => 10000,
        'paid_amount' => 0,
        'due_amount' => 10000,
    ]);

    Income::factory()->create([
        'student_id' => $student1->id,
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'student_id' => $student2->id,
        'created_by' => $this->user->id,
    ]);

    $student1Incomes = Income::forStudent($student1->id)->get();

    expect($student1Incomes)->toHaveCount(1)
        ->and($student1Incomes->first()->student_id)->toBe($student1->id);
});

test('income can be filtered to show only payment-linked incomes', function () {
    // Create a user for the student
    $studentUser = User::factory()->create();
    
    $student = Student::create([
        'user_id' => $studentUser->id,
        'name_bn' => 'Test Student',
        'registration_no' => 'TEST-2024-005',
        'batch_id' => null,
        'total_amount' => 10000,
        'paid_amount' => 0,
        'due_amount' => 10000,
    ]);
    
    $payment = Payment::create([
        'student_id' => $student->id,
        'amount' => 5000,
        'payment_method' => 'cash',
        'payment_date' => now(),
        'status' => 'completed',
    ]);

    Income::factory()->create([
        'payment_id' => $payment->id,
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'payment_id' => null,
        'created_by' => $this->user->id,
    ]);

    $paymentIncomes = Income::fromPayments()->get();

    expect($paymentIncomes)->toHaveCount(1)
        ->and($paymentIncomes->first()->payment_id)->toBe($payment->id);
});

test('income can be filtered to show only manual entries', function () {
    // Create a user for the student
    $studentUser = User::factory()->create();
    
    $student = Student::create([
        'user_id' => $studentUser->id,
        'name_bn' => 'Test Student',
        'registration_no' => 'TEST-2024-006',
        'batch_id' => null,
        'total_amount' => 10000,
        'paid_amount' => 0,
        'due_amount' => 10000,
    ]);
    
    $payment = Payment::create([
        'student_id' => $student->id,
        'amount' => 5000,
        'payment_method' => 'cash',
        'payment_date' => now(),
        'status' => 'completed',
    ]);

    Income::factory()->create([
        'payment_id' => $payment->id,
        'created_by' => $this->user->id,
    ]);
    Income::factory()->create([
        'payment_id' => null,
        'created_by' => $this->user->id,
    ]);

    $manualIncomes = Income::manual()->get();

    expect($manualIncomes)->toHaveCount(1)
        ->and($manualIncomes->first()->payment_id)->toBeNull();
});

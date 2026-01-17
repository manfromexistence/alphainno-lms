<?php

use App\Models\Announcement;
use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Student;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('announcement model has correct fillable attributes', function () {
    $fillable = [
        'title',
        'content',
        'target_type',
        'target_id',
        'priority',
        'starts_at',
        'expires_at',
        'is_active',
        'created_by',
    ];

    expect((new Announcement())->getFillable())->toBe($fillable);
});

test('announcement model casts timestamps correctly', function () {
    $announcement = Announcement::factory()->create([
        'starts_at' => '2024-01-15 10:00:00',
        'expires_at' => '2024-01-20 10:00:00',
        'created_by' => $this->user->id,
    ]);

    expect($announcement->starts_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($announcement->expires_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('announcement model casts is_active to boolean', function () {
    $announcement = Announcement::factory()->create([
        'is_active' => 1,
        'created_by' => $this->user->id,
    ]);

    expect($announcement->is_active)->toBeBool()
        ->and($announcement->is_active)->toBeTrue();
});

test('announcement model has target types constant', function () {
    $expectedTypes = ['all', 'batch', 'course'];

    expect(Announcement::TARGET_TYPES)->toBe($expectedTypes);
});

test('announcement model has priorities constant', function () {
    $expectedPriorities = ['normal', 'high', 'urgent'];

    expect(Announcement::PRIORITIES)->toBe($expectedPriorities);
});

test('announcement belongs to creator user', function () {
    $announcement = Announcement::factory()->create([
        'created_by' => $this->user->id,
    ]);

    expect($announcement->creator)->toBeInstanceOf(User::class)
        ->and($announcement->creator->id)->toBe($this->user->id);
});

test('announcement belongs to batch when target type is batch', function () {
    $batch = Batch::factory()->create();
    $announcement = Announcement::factory()->forBatch($batch->id)->create([
        'created_by' => $this->user->id,
    ]);

    expect($announcement->batch)->toBeInstanceOf(Batch::class)
        ->and($announcement->batch->id)->toBe($batch->id);
});

test('announcement belongs to course when target type is course', function () {
    $course = Course::factory()->create();
    $announcement = Announcement::factory()->forCourse($course->id)->create([
        'created_by' => $this->user->id,
    ]);

    expect($announcement->course)->toBeInstanceOf(Course::class)
        ->and($announcement->course->id)->toBe($course->id);
});

test('scopeActive filters only active announcements', function () {
    // Active announcement (no start/end dates)
    Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => null,
        'expires_at' => null,
        'created_by' => $this->user->id,
    ]);

    // Active announcement (within date range)
    Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => now()->subDays(1),
        'expires_at' => now()->addDays(1),
        'created_by' => $this->user->id,
    ]);

    // Inactive announcement
    Announcement::factory()->create([
        'is_active' => false,
        'created_by' => $this->user->id,
    ]);

    // Expired announcement
    Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => now()->subDays(10),
        'expires_at' => now()->subDays(1),
        'created_by' => $this->user->id,
    ]);

    // Future announcement
    Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => now()->addDays(1),
        'expires_at' => now()->addDays(10),
        'created_by' => $this->user->id,
    ]);

    $activeAnnouncements = Announcement::active()->get();

    expect($activeAnnouncements)->toHaveCount(2);
});

test('scopeForStudent filters announcements for all target type', function () {
    $batch = Batch::factory()->create();
    $student = Student::factory()->create(['batch_id' => $batch->id]);

    // Announcement for all
    Announcement::factory()->forAll()->create([
        'created_by' => $this->user->id,
    ]);

    // Announcement for different batch
    Announcement::factory()->forBatch()->create([
        'created_by' => $this->user->id,
    ]);

    $studentAnnouncements = Announcement::forStudent($student)->get();

    expect($studentAnnouncements)->toHaveCount(1)
        ->and($studentAnnouncements->first()->target_type)->toBe('all');
});

test('scopeForStudent filters announcements for student batch', function () {
    $batch = Batch::factory()->create();
    $student = Student::factory()->create(['batch_id' => $batch->id]);

    // Announcement for student's batch
    Announcement::factory()->forBatch($batch->id)->create([
        'created_by' => $this->user->id,
    ]);

    // Announcement for different batch
    $otherBatch = Batch::factory()->create();
    Announcement::factory()->forBatch($otherBatch->id)->create([
        'created_by' => $this->user->id,
    ]);

    $studentAnnouncements = Announcement::forStudent($student)->get();

    expect($studentAnnouncements)->toHaveCount(1)
        ->and($studentAnnouncements->first()->target_id)->toBe($batch->id);
});

test('scopeForStudent filters announcements for student course', function () {
    $course = Course::factory()->create();
    $batch = Batch::factory()->create(['course_id' => $course->id]);
    $student = Student::factory()->create(['batch_id' => $batch->id]);

    // Announcement for student's course
    Announcement::factory()->forCourse($course->id)->create([
        'created_by' => $this->user->id,
    ]);

    // Announcement for different course
    $otherCourse = Course::factory()->create();
    Announcement::factory()->forCourse($otherCourse->id)->create([
        'created_by' => $this->user->id,
    ]);

    $studentAnnouncements = Announcement::forStudent($student)->get();

    expect($studentAnnouncements)->toHaveCount(1)
        ->and($studentAnnouncements->first()->target_id)->toBe($course->id);
});

test('scopeWithPriority filters announcements by priority', function () {
    Announcement::factory()->urgent()->create(['created_by' => $this->user->id]);
    Announcement::factory()->highPriority()->create(['created_by' => $this->user->id]);
    Announcement::factory()->normalPriority()->create(['created_by' => $this->user->id]);

    $urgentAnnouncements = Announcement::withPriority('urgent')->get();

    expect($urgentAnnouncements)->toHaveCount(1)
        ->and($urgentAnnouncements->first()->priority)->toBe('urgent');
});

test('scopeUrgent filters only urgent announcements', function () {
    Announcement::factory()->urgent()->create(['created_by' => $this->user->id]);
    Announcement::factory()->highPriority()->create(['created_by' => $this->user->id]);
    Announcement::factory()->normalPriority()->create(['created_by' => $this->user->id]);

    $urgentAnnouncements = Announcement::urgent()->get();

    expect($urgentAnnouncements)->toHaveCount(1)
        ->and($urgentAnnouncements->first()->priority)->toBe('urgent');
});

test('scopeHighPriority filters high and urgent announcements', function () {
    Announcement::factory()->urgent()->create(['created_by' => $this->user->id]);
    Announcement::factory()->highPriority()->create(['created_by' => $this->user->id]);
    Announcement::factory()->normalPriority()->create(['created_by' => $this->user->id]);

    $highPriorityAnnouncements = Announcement::highPriority()->get();

    expect($highPriorityAnnouncements)->toHaveCount(2);
});

test('isCurrentlyActive returns true for active announcement', function () {
    $announcement = Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => now()->subDays(1),
        'expires_at' => now()->addDays(1),
        'created_by' => $this->user->id,
    ]);

    expect($announcement->isCurrentlyActive())->toBeTrue();
});

test('isCurrentlyActive returns false for inactive announcement', function () {
    $announcement = Announcement::factory()->create([
        'is_active' => false,
        'created_by' => $this->user->id,
    ]);

    expect($announcement->isCurrentlyActive())->toBeFalse();
});

test('isCurrentlyActive returns false for expired announcement', function () {
    $announcement = Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => now()->subDays(10),
        'expires_at' => now()->subDays(1),
        'created_by' => $this->user->id,
    ]);

    expect($announcement->isCurrentlyActive())->toBeFalse();
});

test('isCurrentlyActive returns false for future announcement', function () {
    $announcement = Announcement::factory()->create([
        'is_active' => true,
        'starts_at' => now()->addDays(1),
        'expires_at' => now()->addDays(10),
        'created_by' => $this->user->id,
    ]);

    expect($announcement->isCurrentlyActive())->toBeFalse();
});

test('isExpired returns true for expired announcement', function () {
    $announcement = Announcement::factory()->create([
        'expires_at' => now()->subDays(1),
        'created_by' => $this->user->id,
    ]);

    expect($announcement->isExpired())->toBeTrue();
});

test('isExpired returns false for active announcement', function () {
    $announcement = Announcement::factory()->create([
        'expires_at' => now()->addDays(1),
        'created_by' => $this->user->id,
    ]);

    expect($announcement->isExpired())->toBeFalse();
});

test('getTargetNameAttribute returns All for all target type', function () {
    $announcement = Announcement::factory()->forAll()->create([
        'created_by' => $this->user->id,
    ]);

    expect($announcement->target_name)->toBe('All');
});

test('getTargetNameAttribute returns batch name for batch target type', function () {
    $batch = Batch::factory()->create(['name' => 'Test Batch']);
    $announcement = Announcement::factory()->forBatch($batch->id)->create([
        'created_by' => $this->user->id,
    ]);

    expect($announcement->target_name)->toBe('Test Batch');
});

test('getTargetNameAttribute returns course name for course target type', function () {
    $course = Course::factory()->create(['name' => 'Test Course']);
    $announcement = Announcement::factory()->forCourse($course->id)->create([
        'created_by' => $this->user->id,
    ]);

    expect($announcement->target_name)->toBe('Test Course');
});

test('getPriorityColorAttribute returns correct color for priority', function () {
    $urgentAnnouncement = Announcement::factory()->urgent()->create(['created_by' => $this->user->id]);
    $highAnnouncement = Announcement::factory()->highPriority()->create(['created_by' => $this->user->id]);
    $normalAnnouncement = Announcement::factory()->normalPriority()->create(['created_by' => $this->user->id]);

    expect($urgentAnnouncement->priority_color)->toBe('red')
        ->and($highAnnouncement->priority_color)->toBe('orange')
        ->and($normalAnnouncement->priority_color)->toBe('blue');
});

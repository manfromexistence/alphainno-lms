<?php

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\StudentIdGenerator;
use App\Services\StudentService;

beforeEach(function () {
    $this->settingsService = Mockery::mock(SettingsService::class);
    $this->settingsService->shouldReceive('get')
        ->with('student_id_format', '{YEAR}{BATCH}{SEQ:4}')
        ->andReturn('{YEAR}{BATCH}{SEQ:4}');
    $this->settingsService->shouldReceive('get')
        ->with('student_id_sequence_start', 1)
        ->andReturn(1);

    $this->idGenerator = new StudentIdGenerator($this->settingsService);
    $this->service = new StudentService($this->idGenerator);
});

describe('create', function () {
    test('generates registration number automatically when not provided', function () {
        $batch = Batch::factory()->create(['code' => 'TEST']);
        $user = User::factory()->create();

        $student = $this->service->create([
            'user_id' => $user->id,
            'batch_id' => $batch->id,
            'name_bn' => 'Test Student',
            'total_amount' => 10000,
            'paid_amount' => 5000,
        ]);

        expect($student->registration_no)->not->toBeNull()
            ->and($student->registration_no)->toContain(date('Y'))
            ->and($student->registration_no)->toContain('TEST');
    });

    test('uses provided registration number when given', function () {
        $batch = Batch::factory()->create();
        $user = User::factory()->create();

        $student = $this->service->create([
            'user_id' => $user->id,
            'batch_id' => $batch->id,
            'registration_no' => 'CUSTOM-REG-001',
            'name_bn' => 'Test Student',
            'total_amount' => 10000,
            'paid_amount' => 5000,
        ]);

        expect($student->registration_no)->toBe('CUSTOM-REG-001');
    });

    test('calculates due_amount from total_amount and paid_amount', function () {
        $batch = Batch::factory()->create();
        $user = User::factory()->create();

        $student = $this->service->create([
            'user_id' => $user->id,
            'batch_id' => $batch->id,
            'name_bn' => 'Test Student',
            'total_amount' => 15000,
            'paid_amount' => 6000,
        ]);

        expect($student->due_amount)->toBe('9000.00');
    });

    test('sets due_amount to zero when fully paid', function () {
        $batch = Batch::factory()->create();
        $user = User::factory()->create();

        $student = $this->service->create([
            'user_id' => $user->id,
            'batch_id' => $batch->id,
            'name_bn' => 'Test Student',
            'total_amount' => 10000,
            'paid_amount' => 10000,
        ]);

        expect($student->due_amount)->toBe('0.00');
    });
});

describe('update', function () {
    test('recalculates due_amount when total_amount changes', function () {
        $student = Student::factory()->create([
            'total_amount' => 10000,
            'paid_amount' => 5000,
            'due_amount' => 5000,
        ]);

        $updatedStudent = $this->service->update($student, [
            'total_amount' => 15000,
        ]);

        expect($updatedStudent->due_amount)->toBe('10000.00');
    });

    test('recalculates due_amount when paid_amount changes', function () {
        $student = Student::factory()->create([
            'total_amount' => 10000,
            'paid_amount' => 5000,
            'due_amount' => 5000,
        ]);

        $updatedStudent = $this->service->update($student, [
            'paid_amount' => 8000,
        ]);

        expect($updatedStudent->due_amount)->toBe('2000.00');
    });

    test('recalculates due_amount when both amounts change', function () {
        $student = Student::factory()->create([
            'total_amount' => 10000,
            'paid_amount' => 5000,
            'due_amount' => 5000,
        ]);

        $updatedStudent = $this->service->update($student, [
            'total_amount' => 20000,
            'paid_amount' => 12000,
        ]);

        expect($updatedStudent->due_amount)->toBe('8000.00');
    });

    test('does not recalculate due_amount when amounts are not changed', function () {
        $student = Student::factory()->create([
            'total_amount' => 10000,
            'paid_amount' => 5000,
            'due_amount' => 5000,
        ]);

        $updatedStudent = $this->service->update($student, [
            'name_bn' => 'Updated Name',
        ]);

        expect($updatedStudent->due_amount)->toBe('5000.00')
            ->and($updatedStudent->name_bn)->toBe('Updated Name');
    });
});

describe('delete', function () {
    test('removes related attendances', function () {
        $student = Student::factory()->create();
        Attendance::create([
            'student_id' => $student->id,
            'batch_id' => $student->batch_id,
            'date' => now(),
            'status' => 'present',
        ]);

        expect(Attendance::where('student_id', $student->id)->count())->toBe(1);

        $this->service->delete($student);

        expect(Attendance::where('student_id', $student->id)->count())->toBe(0);
    });

    test('removes related payments', function () {
        $student = Student::factory()->create();
        Payment::create([
            'student_id' => $student->id,
            'amount' => 1000,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'status' => 'completed',
        ]);

        expect(Payment::where('student_id', $student->id)->count())->toBe(1);

        $this->service->delete($student);

        expect(Payment::where('student_id', $student->id)->count())->toBe(0);
    });

    test('removes related invoices', function () {
        $student = Student::factory()->create();
        Invoice::create([
            'student_id' => $student->id,
            'invoice_number' => 'INV-001',
            'amount' => 5000,
            'due_date' => now()->addDays(30),
            'status' => 'pending',
        ]);

        expect(Invoice::where('student_id', $student->id)->count())->toBe(1);

        $this->service->delete($student);

        expect(Invoice::where('student_id', $student->id)->count())->toBe(0);
    });

    test('removes related results', function () {
        $student = Student::factory()->create();
        $exam = Exam::create([
            'title' => 'Test Exam',
            'type' => 'mcq',
            'batch_id' => $student->batch_id,
            'total_marks' => 100,
            'pass_marks' => 40,
            'status' => 'active',
        ]);
        ExamResult::create([
            'student_id' => $student->id,
            'exam_id' => $exam->id,
            'subject_name' => 'Math',
            'marks' => 80,
            'grade' => 'A',
            'total_marks' => 100,
            'obtained_marks' => 80,
        ]);

        expect(ExamResult::where('student_id', $student->id)->count())->toBe(1);

        $this->service->delete($student);

        expect(ExamResult::where('student_id', $student->id)->count())->toBe(0);
    });

    test('deletes the student record', function () {
        $student = Student::factory()->create();
        $studentId = $student->id;

        $result = $this->service->delete($student);

        expect($result)->toBeTrue()
            ->and(Student::find($studentId))->toBeNull();
    });
});

describe('assignToBatch', function () {
    test('assigns student to batch successfully', function () {
        $batch = Batch::factory()->create(['max_students' => 30]);
        $student = Student::factory()->create();

        $updatedStudent = $this->service->assignToBatch($student, $batch->id);

        expect($updatedStudent->batch_id)->toBe($batch->id);
    });

    test('throws exception when batch is at capacity', function () {
        $batch = Batch::factory()->create(['max_students' => 2]);

        Student::factory()->count(2)->create(['batch_id' => $batch->id]);

        $newStudent = Student::factory()->create();

        expect(fn () => $this->service->assignToBatch($newStudent, $batch->id))
            ->toThrow(RuntimeException::class, 'Batch has reached maximum capacity');
    });

    test('allows assignment when batch has no max_students limit', function () {
        $batch = Batch::factory()->create(['max_students' => null]);
        $student = Student::factory()->create();

        $updatedStudent = $this->service->assignToBatch($student, $batch->id);

        expect($updatedStudent->batch_id)->toBe($batch->id);
    });
});

describe('getPaginated', function () {
    test('filters by search term matching name_bn', function () {
        Student::factory()->create(['name_bn' => 'আব্দুল করিম']);
        Student::factory()->create(['name_bn' => 'মোহাম্মদ হাসান']);

        $result = $this->service->getPaginated(['search' => 'আব্দুল']);

        expect($result->total())->toBe(1)
            ->and($result->first()->name_bn)->toBe('আব্দুল করিম');
    });

    test('filters by search term matching registration_no', function () {
        Student::factory()->create(['registration_no' => '2026-ABC-0001']);
        Student::factory()->create(['registration_no' => '2026-XYZ-0002']);

        $result = $this->service->getPaginated(['search' => 'ABC']);

        expect($result->total())->toBe(1)
            ->and($result->first()->registration_no)->toBe('2026-ABC-0001');
    });

    test('filters by search term matching phone', function () {
        Student::factory()->create(['phone' => '01712345678']);
        Student::factory()->create(['phone' => '01898765432']);

        $result = $this->service->getPaginated(['search' => '01712']);

        expect($result->total())->toBe(1)
            ->and($result->first()->phone)->toBe('01712345678');
    });

    test('filters by year', function () {
        $batch = Batch::factory()->create();
        
        $thisYear = Student::factory()->create(['batch_id' => $batch->id]);
        
        $lastYear = Student::factory()->create(['batch_id' => $batch->id]);
        \Illuminate\Support\Facades\DB::table('students')
            ->where('id', $lastYear->id)
            ->update(['created_at' => now()->subYear()]);

        $result = $this->service->getPaginated(['year' => date('Y'), 'batch_id' => $batch->id]);

        expect($result->total())->toBe(1);
    });

    test('filters by batch_id', function () {
        $batch1 = Batch::factory()->create();
        $batch2 = Batch::factory()->create();

        Student::factory()->create(['batch_id' => $batch1->id]);
        Student::factory()->create(['batch_id' => $batch2->id]);

        $result = $this->service->getPaginated(['batch_id' => $batch1->id]);

        expect($result->total())->toBe(1)
            ->and($result->first()->batch_id)->toBe($batch1->id);
    });

    test('filters by class', function () {
        Student::factory()->create(['class' => 'Class A']);
        Student::factory()->create(['class' => 'Class B']);

        $result = $this->service->getPaginated(['class' => 'Class A']);

        expect($result->total())->toBe(1)
            ->and($result->first()->class)->toBe('Class A');
    });

    test('filters by with_dues', function () {
        Student::factory()->create(['due_amount' => 5000]);
        Student::factory()->create(['due_amount' => 0]);

        $result = $this->service->getPaginated(['with_dues' => true]);

        expect($result->total())->toBe(1)
            ->and((float) $result->first()->due_amount)->toBeGreaterThan(0);
    });

    test('filters by featured', function () {
        Student::factory()->create(['featured' => true]);
        Student::factory()->create(['featured' => false]);

        $result = $this->service->getPaginated(['featured' => true]);

        expect($result->total())->toBe(1)
            ->and($result->first()->featured)->toBeTrue();
    });

    test('applies multiple filters together', function () {
        $batch = Batch::factory()->create();

        Student::factory()->create([
            'batch_id' => $batch->id,
            'class' => 'A',
            'due_amount' => 5000,
        ]);
        Student::factory()->create([
            'batch_id' => $batch->id,
            'class' => 'B',
            'due_amount' => 0,
        ]);
        Student::factory()->create([
            'class' => 'A',
            'due_amount' => 5000,
        ]);

        $result = $this->service->getPaginated([
            'batch_id' => $batch->id,
            'class' => 'A',
            'with_dues' => true,
        ]);

        expect($result->total())->toBe(1);
    });

    test('paginates results correctly', function () {
        Student::factory()->count(25)->create();

        $result = $this->service->getPaginated([], 10);

        expect($result->total())->toBe(25)
            ->and($result->perPage())->toBe(10)
            ->and($result->count())->toBe(10);
    });
});

describe('bulkAssignToBatch', function () {
    test('assigns multiple students to batch successfully', function () {
        $batch = Batch::factory()->create(['max_students' => 10]);
        $students = Student::factory()->count(3)->create();
        $studentIds = $students->pluck('id')->toArray();

        $count = $this->service->bulkAssignToBatch($studentIds, $batch->id);

        expect($count)->toBe(3);
        foreach ($studentIds as $id) {
            expect(Student::find($id)->batch_id)->toBe($batch->id);
        }
    });

    test('throws exception when adding students would exceed capacity', function () {
        $batch = Batch::factory()->create(['max_students' => 5]);

        Student::factory()->count(3)->create(['batch_id' => $batch->id]);

        $newStudents = Student::factory()->count(4)->create();
        $studentIds = $newStudents->pluck('id')->toArray();

        expect(fn () => $this->service->bulkAssignToBatch($studentIds, $batch->id))
            ->toThrow(RuntimeException::class, 'Adding these students would exceed batch capacity');
    });

    test('allows bulk assignment when exactly at capacity', function () {
        $batch = Batch::factory()->create(['max_students' => 5]);

        Student::factory()->count(2)->create(['batch_id' => $batch->id]);

        $newStudents = Student::factory()->count(3)->create();
        $studentIds = $newStudents->pluck('id')->toArray();

        $count = $this->service->bulkAssignToBatch($studentIds, $batch->id);

        expect($count)->toBe(3);
    });

    test('allows bulk assignment when batch has no max_students limit', function () {
        $batch = Batch::factory()->create(['max_students' => null]);
        $students = Student::factory()->count(100)->create();
        $studentIds = $students->pluck('id')->toArray();

        $count = $this->service->bulkAssignToBatch($studentIds, $batch->id);

        expect($count)->toBe(100);
    });
});

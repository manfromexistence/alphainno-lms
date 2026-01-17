<?php

use App\Models\Batch;
use App\Models\Course;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->adminRole = Role::firstOrCreate(
        ['slug' => 'super-admin'],
        ['name' => 'Super Admin', 'description' => 'Administrator with full access']
    );

    $this->studentRole = Role::firstOrCreate(
        ['slug' => 'student'],
        ['name' => 'Student', 'description' => 'Student role']
    );

    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($this->adminRole->id);
});

describe('authentication', function () {
    test('index redirects unauthenticated users to login', function () {
        $response = $this->get(route('dashboard.students.index'));

        $response->assertRedirect(route('login'));
    });

    test('create redirects unauthenticated users to login', function () {
        $response = $this->get(route('dashboard.students.create'));

        $response->assertRedirect(route('login'));
    });

    test('store redirects unauthenticated users to login', function () {
        $response = $this->post(route('dashboard.students.store'), [
            'name_bn' => 'Test Student',
            'phone' => '01700000000',
        ]);

        $response->assertRedirect(route('login'));
    });

    test('show redirects unauthenticated users to login', function () {
        $student = Student::factory()->create();

        $response = $this->get(route('dashboard.students.show', $student));

        $response->assertRedirect(route('login'));
    });

    test('edit redirects unauthenticated users to login', function () {
        $student = Student::factory()->create();

        $response = $this->get(route('dashboard.students.edit', $student));

        $response->assertRedirect(route('login'));
    });

    test('update redirects unauthenticated users to login', function () {
        $student = Student::factory()->create();

        $response = $this->put(route('dashboard.students.update', $student), [
            'name_bn' => 'Updated Name',
            'phone' => '01700000000',
        ]);

        $response->assertRedirect(route('login'));
    });

    test('destroy redirects unauthenticated users to login', function () {
        $student = Student::factory()->create();

        $response = $this->delete(route('dashboard.students.destroy', $student));

        $response->assertRedirect(route('login'));
    });
});

describe('index', function () {
    test('displays students list', function () {
        $students = Student::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index'));

        $response->assertOk();
        $response->assertViewIs('dashboard.students.index');
        $response->assertViewHas('students');
        $response->assertViewHas('batches');
        $response->assertViewHas('years');
        $response->assertViewHas('classes');
    });

    test('filters students by search term', function () {
        $student1 = Student::factory()->create(['name_bn' => 'জন ডো']);
        $student2 = Student::factory()->create(['name_bn' => 'জেন স্মিথ']);

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', ['search' => 'জন']));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) {
            return $filters['search'] === 'জন';
        });
    });

    test('filters students by year', function () {
        $currentYear = date('Y');
        Student::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', ['year' => $currentYear]));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) use ($currentYear) {
            return $filters['year'] === (string) $currentYear;
        });
    });

    test('filters students by batch_id', function () {
        $batch = Batch::factory()->create();
        Student::factory()->create(['batch_id' => $batch->id]);
        Student::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', ['batch_id' => $batch->id]));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) use ($batch) {
            return $filters['batch_id'] === (string) $batch->id;
        });
    });

    test('filters students by class', function () {
        Student::factory()->create(['class' => '10']);
        Student::factory()->create(['class' => '12']);

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', ['class' => '10']));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) {
            return $filters['class'] === '10';
        });
    });

    test('filters students with dues', function () {
        Student::factory()->withDues()->create();
        Student::factory()->noDues()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', ['with_dues' => '1']));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) {
            return $filters['with_dues'] === true;
        });
    });

    test('filters featured students', function () {
        Student::factory()->featured()->create();
        Student::factory()->create(['featured' => false]);

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', ['featured' => '1']));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) {
            return $filters['featured'] === true;
        });
    });

    test('sorts students by specified field and direction', function () {
        Student::factory()->create(['name_bn' => 'AAA']);
        Student::factory()->create(['name_bn' => 'ZZZ']);

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.index', [
                'sort_by' => 'name_bn',
                'sort_dir' => 'asc',
            ]));

        $response->assertOk();
        $response->assertViewHas('filters', function ($filters) {
            return $filters['sort_by'] === 'name_bn' && $filters['sort_dir'] === 'asc';
        });
    });
});

describe('create', function () {
    test('displays create form with batches and classes', function () {
        Batch::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.create'));

        $response->assertOk();
        $response->assertViewIs('dashboard.students.create');
        $response->assertViewHas('batches');
        $response->assertViewHas('classes');
    });
});

describe('store', function () {
    test('creates student with user account and assigns role', function () {
        $batch = Batch::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), [
                'name_bn' => 'নতুন শিক্ষার্থী',
                'phone' => '01712345678',
                'batch_id' => $batch->id,
                'class' => '10',
                'gender' => 'Male',
            ]);

        $response->assertRedirect(route('dashboard.students.index'));
        $response->assertSessionHas('success');

        $student = Student::where('name_bn', 'নতুন শিক্ষার্থী')->first();
        expect($student)->not->toBeNull();
        expect($student->phone)->toBe('01712345678');
        expect($student->batch_id)->toBe($batch->id);
        expect($student->user)->not->toBeNull();
        expect($student->user->roles->pluck('slug'))->toContain('student');
    });

    test('generates random password not equal to hardcoded password', function () {
        $batch = Batch::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), [
                'name_bn' => 'পাসওয়ার্ড টেস্ট',
                'phone' => '01700000001',
                'batch_id' => $batch->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('generated_password');

        $generatedPassword = session('generated_password');
        expect($generatedPassword)->not->toBe('password');
        expect(strlen($generatedPassword))->toBe(12);
    });

    test('flashes generated password to session for display', function () {
        $batch = Batch::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), [
                'name_bn' => 'সেশন টেস্ট',
                'phone' => '01700000002',
                'batch_id' => $batch->id,
            ]);

        $response->assertSessionHas('generated_password');
        $password = session('generated_password');
        expect($password)->toBeString();
        expect(strlen($password))->toBeGreaterThan(0);
    });

    test('created user password is hashed correctly', function () {
        $batch = Batch::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), [
                'name_bn' => 'হ্যাশ টেস্ট',
                'phone' => '01700000003',
                'batch_id' => $batch->id,
            ]);

        $student = Student::where('name_bn', 'হ্যাশ টেস্ট')->first();
        $generatedPassword = session('generated_password');

        expect(Hash::check($generatedPassword, $student->user->password))->toBeTrue();
    });

    test('validates required fields', function () {
        $response = $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), []);

        $response->assertSessionHasErrors(['name_bn', 'phone']);
    });

    test('validates batch exists', function () {
        $response = $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), [
                'name_bn' => 'টেস্ট',
                'phone' => '01700000004',
                'batch_id' => 99999,
            ]);

        $response->assertSessionHasErrors(['batch_id']);
    });

    test('sets course name from course_id when provided', function () {
        $course = Course::factory()->create(['name' => 'Physics Course']);
        $batch = Batch::factory()->create(['course_id' => $course->id]);

        $this->actingAs($this->admin)
            ->post(route('dashboard.students.store'), [
                'name_bn' => 'কোর্স টেস্ট',
                'phone' => '01700000005',
                'batch_id' => $batch->id,
                'course_id' => $course->id,
            ]);

        $student = Student::where('name_bn', 'কোর্স টেস্ট')->first();
        expect($student->course_name)->toBe('Physics Course');
    });
});

describe('show', function () {
    test('displays student details with relations', function () {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.show', $student));

        $response->assertOk();
        $response->assertViewIs('dashboard.students.show');
        $response->assertViewHas('student');
    });

    test('returns 404 for non-existent student', function () {
        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.show', 99999));

        $response->assertNotFound();
    });
});

describe('edit', function () {
    test('displays edit form with student data', function () {
        $student = Student::factory()->create();
        Batch::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.edit', $student));

        $response->assertOk();
        $response->assertViewIs('dashboard.students.edit');
        $response->assertViewHas('student');
        $response->assertViewHas('batches');
        $response->assertViewHas('classes');
        $response->assertViewHas('courses');
    });

    test('loads student with user and batch relations', function () {
        $batch = Batch::factory()->create();
        $user = User::factory()->create();
        $student = Student::factory()->create([
            'user_id' => $user->id,
            'batch_id' => $batch->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.students.edit', $student));

        $response->assertOk();
        $response->assertViewHas('student', function ($viewStudent) use ($user, $batch) {
            return $viewStudent->user->id === $user->id
                && $viewStudent->batch->id === $batch->id;
        });
    });
});

describe('update', function () {
    test('updates student data', function () {
        $student = Student::factory()->create();
        $newBatch = Batch::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('dashboard.students.update', $student), [
                'name_bn' => 'আপডেটেড নাম',
                'phone' => '01799999999',
                'batch_id' => $newBatch->id,
            ]);

        $response->assertRedirect(route('dashboard.students.index'));
        $response->assertSessionHas('success');

        $student->refresh();
        expect($student->name_bn)->toBe('আপডেটেড নাম');
        expect($student->phone)->toBe('01799999999');
        expect($student->batch_id)->toBe($newBatch->id);
    });

    test('updates associated user name when name_bn changes', function () {
        $user = User::factory()->create(['name' => 'Old Name']);
        $student = Student::factory()->create([
            'user_id' => $user->id,
            'name_bn' => 'পুরাতন নাম',
            'phone' => '01712345678',
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('dashboard.students.update', $student), [
                'name_bn' => 'নতুন নাম',
                'phone' => '01712345678',
            ]);

        $response->assertRedirect(route('dashboard.students.index'));
        $user->refresh();
        expect($user->name)->toBe('নতুন নাম');
    });

    test('validates unique registration number ignoring current student', function () {
        $student1 = Student::factory()->create(['registration_no' => 'REG-001']);
        $student2 = Student::factory()->create(['registration_no' => 'REG-002']);

        $response = $this->actingAs($this->admin)
            ->put(route('dashboard.students.update', $student2), [
                'name_bn' => 'টেস্ট',
                'phone' => '01700000000',
                'registration_no' => 'REG-001',
            ]);

        $response->assertSessionHasErrors(['registration_no']);
    });

    test('allows keeping same registration number on update', function () {
        $student = Student::factory()->create(['registration_no' => 'REG-SAME']);

        $response = $this->actingAs($this->admin)
            ->put(route('dashboard.students.update', $student), [
                'name_bn' => 'আপডেটেড',
                'phone' => '01700000000',
                'registration_no' => 'REG-SAME',
            ]);

        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors('registration_no');
    });

    test('updates payment amounts and calculates due amount', function () {
        $student = Student::factory()->create([
            'name_bn' => 'টেস্ট শিক্ষার্থী',
            'phone' => '01712345678',
            'total_amount' => 10000,
            'paid_amount' => 5000,
            'due_amount' => 5000,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('dashboard.students.update', $student), [
                'name_bn' => 'টেস্ট শিক্ষার্থী',
                'phone' => '01712345678',
                'total_amount' => 15000,
                'paid_amount' => 10000,
            ]);

        $response->assertRedirect(route('dashboard.students.index'));
        $student->refresh();
        expect((float) $student->total_amount)->toBe(15000.0);
        expect((float) $student->paid_amount)->toBe(10000.0);
        expect((float) $student->due_amount)->toBe(5000.0);
    });
});

describe('destroy', function () {
    test('deletes student and associated user', function () {
        $user = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $user->id]);
        $studentId = $student->id;
        $userId = $user->id;

        $response = $this->actingAs($this->admin)
            ->delete(route('dashboard.students.destroy', $student));

        $response->assertRedirect(route('dashboard.students.index'));
        $response->assertSessionHas('success');

        expect(Student::find($studentId))->toBeNull();
        expect(User::find($userId))->toBeNull();
    });

    test('returns success message after deletion', function () {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('dashboard.students.destroy', $student));

        $response->assertRedirect(route('dashboard.students.index'));
        $response->assertSessionHas('success', 'Student deleted successfully.');
    });

    test('deletes related records when student is deleted', function () {
        $student = Student::factory()->create();
        $studentId = $student->id;
        $userId = $student->user_id;

        $this->actingAs($this->admin)
            ->delete(route('dashboard.students.destroy', $student));

        expect(Student::find($studentId))->toBeNull();
        expect(User::find($userId))->toBeNull();
    });
});

describe('ajax endpoints', function () {
    test('getBatches returns batches for a course', function () {
        $course = Course::factory()->create();
        Batch::factory()->count(3)->create(['course_id' => $course->id]);
        Batch::factory()->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('dashboard.students.get-batches', $course->id));

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'code', 'schedule'],
        ]);
    });

    test('getCourses returns courses for a class', function () {
        Course::factory()->count(2)->create(['class' => 10]);
        Course::factory()->create(['class' => 12]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('dashboard.students.get-courses', 10));

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);
    });
});

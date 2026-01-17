<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class QuickSeeder extends Seeder
{
    public function run(): void
    {
        // SQLite doesn't support FOREIGN_KEY_CHECKS
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } else {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }
        
        // Clear tables
        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();
        User::truncate();
        Role::truncate();
        Permission::truncate();
        Course::truncate();
        Batch::truncate();
        Student::truncate();
        Teacher::truncate();
        
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        // Create roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Teacher', 'slug' => 'teacher'],
            ['name' => 'Student', 'slug' => 'student'],
            ['name' => 'Parent', 'slug' => 'parent'],
        ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create permissions
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users'],
            ['name' => 'Manage Students', 'slug' => 'manage-students'],
            ['name' => 'Manage Teachers', 'slug' => 'manage-teachers'],
            ['name' => 'Manage Courses', 'slug' => 'manage-courses'],
            ['name' => 'Manage Batches', 'slug' => 'manage-batches'],
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard'],
        ];
        
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to super admin
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $superAdminRole->permissions()->attach(Permission::all());

        // Create admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($superAdminRole);

        // Create courses
        $courses = [
            ['name' => 'Web Development', 'code' => 'WEB-101', 'class' => 'HSC', 'price' => 15000, 'status' => 'active'],
            ['name' => 'Data Science', 'code' => 'DS-101', 'class' => 'HSC', 'price' => 20000, 'status' => 'active'],
            ['name' => 'Digital Marketing', 'code' => 'DM-101', 'class' => 'SSC', 'price' => 12000, 'status' => 'active'],
        ];
        
        foreach ($courses as $course) {
            Course::create($course);
        }

        // Create batches
        $courseIds = Course::pluck('id')->toArray();
        foreach ($courseIds as $courseId) {
            Batch::create([
                'name' => 'Batch A',
                'code' => 'BATCH-A-' . $courseId,
                'course_id' => $courseId,
                'schedule' => 'Mon, Wed, Fri - 10:00 AM',
                'max_students' => 30,
                'status' => 'active',
            ]);
        }

        // Create student user
        $studentRole = Role::where('slug', 'student')->first();
        $studentUser = User::create([
            'name' => 'Demo Student',
            'email' => 'student@demo.com',
            'password' => Hash::make('password'),
        ]);
        $studentUser->roles()->attach($studentRole);

        // Create student record
        $batch = Batch::first();
        Student::create([
            'user_id' => $studentUser->id,
            'registration_no' => 'STU-2026-0001',
            'batch_id' => $batch->id,
            'phone' => '01700123456',
            'guardian_phone' => '01900123456',
        ]);

        // Create teacher user
        $teacherRole = Role::where('slug', 'teacher')->first();
        $teacherUser = User::create([
            'name' => 'Demo Teacher',
            'email' => 'teacher@demo.com',
            'password' => Hash::make('password'),
        ]);
        $teacherUser->roles()->attach($teacherRole);

        // Create teacher record
        Teacher::create([
            'user_id' => $teacherUser->id,
            'phone' => '01800123456',
            'department' => 'Computer Science',
            'salary' => 25000,
        ]);

        $this->command->info('Quick seeding completed successfully!');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('Student: student@demo.com / password');
        $this->command->info('Teacher: teacher@demo.com / password');
    }
}

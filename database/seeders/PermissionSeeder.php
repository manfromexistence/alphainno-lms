<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Students
            ['name' => 'View Students', 'slug' => 'students.view', 'module' => 'students'],
            ['name' => 'Create Students', 'slug' => 'students.create', 'module' => 'students'],
            ['name' => 'Edit Students', 'slug' => 'students.edit', 'module' => 'students'],
            ['name' => 'Delete Students', 'slug' => 'students.delete', 'module' => 'students'],

            // Teachers
            ['name' => 'View Teachers', 'slug' => 'teachers.view', 'module' => 'teachers'],
            ['name' => 'Create Teachers', 'slug' => 'teachers.create', 'module' => 'teachers'],
            ['name' => 'Edit Teachers', 'slug' => 'teachers.edit', 'module' => 'teachers'],
            ['name' => 'Delete Teachers', 'slug' => 'teachers.delete', 'module' => 'teachers'],

            // Courses
            ['name' => 'View Courses', 'slug' => 'courses.view', 'module' => 'courses'],
            ['name' => 'Create Courses', 'slug' => 'courses.create', 'module' => 'courses'],
            ['name' => 'Edit Courses', 'slug' => 'courses.edit', 'module' => 'courses'],
            ['name' => 'Delete Courses', 'slug' => 'courses.delete', 'module' => 'courses'],

            // Batches
            ['name' => 'View Batches', 'slug' => 'batches.view', 'module' => 'batches'],
            ['name' => 'Create Batches', 'slug' => 'batches.create', 'module' => 'batches'],
            ['name' => 'Edit Batches', 'slug' => 'batches.edit', 'module' => 'batches'],
            ['name' => 'Delete Batches', 'slug' => 'batches.delete', 'module' => 'batches'],

            // Payments
            ['name' => 'View Payments', 'slug' => 'payments.view', 'module' => 'payments'],
            ['name' => 'Create Payments', 'slug' => 'payments.create', 'module' => 'payments'],
            ['name' => 'Edit Payments', 'slug' => 'payments.edit', 'module' => 'payments'],
            ['name' => 'Delete Payments', 'slug' => 'payments.delete', 'module' => 'payments'],

            // Exams
            ['name' => 'View Exams', 'slug' => 'exams.view', 'module' => 'exams'],
            ['name' => 'Create Exams', 'slug' => 'exams.create', 'module' => 'exams'],
            ['name' => 'Edit Exams', 'slug' => 'exams.edit', 'module' => 'exams'],
            ['name' => 'Delete Exams', 'slug' => 'exams.delete', 'module' => 'exams'],

            // Attendance
            ['name' => 'View Attendance', 'slug' => 'attendance.view', 'module' => 'attendance'],
            ['name' => 'Record Attendance', 'slug' => 'attendance.record', 'module' => 'attendance'],

            // Reports
            ['name' => 'View Reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'module' => 'reports'],

            // Communication
            ['name' => 'View Communication', 'slug' => 'communication.view', 'module' => 'communication'],
            ['name' => 'Send Communication', 'slug' => 'communication.send', 'module' => 'communication'],

            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'module' => 'settings'],
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'module' => 'settings'],

            // Roles
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'roles'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage', 'module' => 'roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        // Super Admin gets all permissions (handled by Gate::before)
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id')->toArray());
        }

        // Admin gets all permissions (same as super-admin)
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $admin->permissions()->sync(Permission::pluck('id')->toArray());
        }

        // Teacher permissions
        $teacher = Role::where('slug', 'teacher')->first();
        if ($teacher) {
            $teacherPerms = Permission::whereIn('slug', [
                'students.view',
                'courses.view',
                'batches.view',
                'attendance.view', 'attendance.record',
                'exams.view', 'exams.create', 'exams.edit',
                'reports.view',
                'communication.view', 'communication.send',
            ])->pluck('id')->toArray();
            $teacher->permissions()->sync($teacherPerms);
        }

        // Student permissions
        $student = Role::where('slug', 'student')->first();
        if ($student) {
            $studentPerms = Permission::whereIn('slug', [
                'courses.view',
                'attendance.view',
                'exams.view',
                'payments.view',
            ])->pluck('id')->toArray();
            $student->permissions()->sync($studentPerms);
        }

        // Parent permissions
        $parent = Role::where('slug', 'parent')->first();
        if ($parent) {
            $parentPerms = Permission::whereIn('slug', [
                'attendance.view',
                'exams.view',
                'payments.view',
                'reports.view',
            ])->pluck('id')->toArray();
            $parent->permissions()->sync($parentPerms);
        }
    }
}

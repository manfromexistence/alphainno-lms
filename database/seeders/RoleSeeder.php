<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all permissions. Can manage system settings, users, roles, and all modules.',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to manage students, teachers, courses, payments, and reports. Cannot modify system settings.',
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Access to assigned courses, student management, attendance tracking, exam creation, and result management.',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Access to enrolled courses, view results, make payments, and view personal information.',
            ],
            [
                'name' => 'Parent',
                'slug' => 'parent',
                'description' => 'Read-only access to children\'s academic data including results, attendance, and fee status.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}

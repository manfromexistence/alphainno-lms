<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@alpha.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        // Create Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@alpha.com'],
            [
                'name' => 'Alpha Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // Create sample Teacher user
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@alpha.com'],
            [
                'name' => 'Demo Teacher',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Teacher role
        $teacherRole = Role::where('slug', 'teacher')->first();
        if ($teacherRole) {
            $teacher->roles()->syncWithoutDetaching([$teacherRole->id]);
        }

        // Create sample Student user
        $student = User::updateOrCreate(
            ['email' => 'student@alpha.com'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Student role
        $studentRole = Role::where('slug', 'student')->first();
        if ($studentRole) {
            $student->roles()->syncWithoutDetaching([$studentRole->id]);
        }
    }
}

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

        // Create Admin user with admin@gmail.com as Super Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role to admin@gmail.com
        if ($superAdminRole) {
            $admin->roles()->sync([$superAdminRole->id]);
        }

        // Also create admin@alpha.com for backward compatibility
        $adminAlpha = User::updateOrCreate(
            ['email' => 'admin@alpha.com'],
            [
                'name' => 'Alpha Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role to admin@alpha.com as well
        if ($superAdminRole) {
            $adminAlpha->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        // Create sample Teacher user with teacher@gmail.com
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@gmail.com'],
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

        // Also create teacher@alpha.com for backward compatibility
        $teacherAlpha = User::updateOrCreate(
            ['email' => 'teacher@alpha.com'],
            [
                'name' => 'Demo Teacher Alpha',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if ($teacherRole) {
            $teacherAlpha->roles()->syncWithoutDetaching([$teacherRole->id]);
        }

        // Create sample Student user with student@gmail.com
        $student = User::updateOrCreate(
            ['email' => 'student@gmail.com'],
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

        // Also create student@alpha.com for backward compatibility
        $studentAlpha = User::updateOrCreate(
            ['email' => 'student@alpha.com'],
            [
                'name' => 'Demo Student Alpha',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if ($studentRole) {
            $studentAlpha->roles()->syncWithoutDetaching([$studentRole->id]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable model events for faster seeding
        \Illuminate\Database\Eloquent\Model::unguard();
        
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AdminUserSeeder::class,
            CourseSeeder::class,
            BatchSeeder::class,
            StudentManagementSeeder::class,
            TeacherManagementSeeder::class,
            // DemoDataSeeder::class, // Skip if not needed
            PageSeeder::class,
            // ClassCoursesBatchesSeeder::class, // Skip if not needed
            // CourseVideoSeeder::class, // Skip if not needed
            // ClassScheduleSeeder::class, // Skip if not needed
            // PaymentInvoiceSeeder::class, // Skip if not needed
            // ExamSeeder::class, // Skip if not needed
            // InventorySeeder::class, // Skip if not needed
            // AccountSeeder::class, // Skip if not needed
            // AdminFeaturesSeeder::class, // Skip if not needed
            // StudentPortalDataSeeder::class, // Skip if not needed
        ]);
        
        \Illuminate\Database\Eloquent\Model::reguard();
    }
}

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
            PageSeeder::class,
            
            // Comprehensive data seeder - populates all missing data
            ComprehensiveDataSeeder::class,
            
            // Optional seeders (uncomment if needed)
            // DemoDataSeeder::class,
            // ClassCoursesBatchesSeeder::class,
            // InventorySeeder::class,
            // AccountSeeder::class,
            // AdminFeaturesSeeder::class,
        ]);
        
        \Illuminate\Database\Eloquent\Model::reguard();
    }
}

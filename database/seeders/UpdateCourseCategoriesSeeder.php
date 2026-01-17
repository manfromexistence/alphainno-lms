<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class UpdateCourseCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Programming', 'Science', 'Mathematics', 'Business', 'Design', 'Language'];
        
        $courses = Course::all();
        
        foreach ($courses as $course) {
            if (!$course->category) {
                $course->update(['category' => $categories[array_rand($categories)]]);
            }
        }
        
        echo "Updated " . $courses->count() . " courses with categories\n";
    }
}

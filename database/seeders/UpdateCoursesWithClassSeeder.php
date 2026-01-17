<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Student;

class UpdateCoursesWithClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Updating existing courses with class information...');
        
        // Get all courses
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please run CourseSeeder first.');
            return;
        }
        
        // Assign classes to existing courses (distribute evenly across classes 1-12)
        $classNumbers = range(1, 12);
        $coursesPerClass = ceil($courses->count() / 12);
        
        foreach ($courses as $index => $course) {
            $classNumber = $classNumbers[floor($index / $coursesPerClass)] ?? 12;
            
            $course->update([
                'class' => $classNumber,
            ]);
            
            $this->command->info("Updated course '{$course->name}' to Class {$classNumber}");
        }
        
        // Update batches to reflect course classes
        $batches = Batch::with('course')->get();
        foreach ($batches as $batch) {
            if ($batch->course && $batch->course->class) {
                $this->command->info("Batch '{$batch->name}' is now associated with Class {$batch->course->class}");
            }
        }
        
        // Update students to reflect their batch's class
        $students = Student::with('batch.course')->get();
        foreach ($students as $student) {
            if ($student->batch && $student->batch->course && $student->batch->course->class) {
                $student->update([
                    'class' => $student->batch->course->class,
                ]);
                $this->command->info("Updated student '{$student->user->name}' to Class {$student->batch->course->class}");
            }
        }
        
        $this->command->info('Successfully updated all courses, batches, and students with class information!');
    }
}

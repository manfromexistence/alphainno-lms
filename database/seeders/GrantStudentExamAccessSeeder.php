<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Exam;

class GrantStudentExamAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder grants the student@gmail.com user access to all exams
     * by removing batch restrictions from all exams.
     */
    public function run(): void
    {
        $email = 'student@gmail.com';
        
        // Find the user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->command->error("User with email '{$email}' not found.");
            return;
        }
        
        // Find the student record
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            $this->command->error("Student profile not found for user '{$email}'.");
            return;
        }
        
        $this->command->info("Student found: {$student->name_bn} (ID: {$student->id})");
        $this->command->info("Current batch: {$student->batch_id}");
        
        // Count exams before
        $totalExams = Exam::count();
        $accessibleBefore = Exam::where(function($query) use ($student) {
            $query->whereNull('batch_id')
                  ->orWhere('batch_id', $student->batch_id);
        })->count();
        
        $this->command->info("Before: Student has access to {$accessibleBefore} out of {$totalExams} exams.");
        
        // Remove batch restrictions from all exams
        // This makes all exams available to all students
        $updated = Exam::whereNotNull('batch_id')->update(['batch_id' => null]);
        
        $this->command->info("Removed batch restrictions from {$updated} exams.");
        $this->command->info("All {$totalExams} exams are now available to all students.");
        $this->command->info("✓ Student '{$email}' now has access to all exams!");
    }
}

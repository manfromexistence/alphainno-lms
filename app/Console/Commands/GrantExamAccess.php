<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Student;
use App\Models\Exam;

class GrantExamAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:grant-access {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant a student access to all exams by removing batch restrictions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Find the user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }
        
        // Check if user is a student
        if (!$user->isStudent()) {
            $this->error("User '{$email}' is not a student.");
            return 1;
        }
        
        // Find the student record
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            $this->error("Student profile not found for user '{$email}'.");
            return 1;
        }
        
        $this->info("Student found: {$student->name_bn} (ID: {$student->id})");
        $this->info("Current batch: {$student->batch_id}");
        
        // Option 1: Remove batch restrictions from all exams
        if ($this->confirm('Do you want to remove batch restrictions from ALL exams? (This will make all exams available to all students)', false)) {
            $count = Exam::whereNotNull('batch_id')->update(['batch_id' => null]);
            $this->info("Removed batch restrictions from {$count} exams.");
            $this->info("All exams are now available to all students.");
            return 0;
        }
        
        // Option 2: Show current exam access
        $this->info("\nCurrent exam access:");
        $accessibleExams = Exam::where(function($query) use ($student) {
            $query->whereNull('batch_id')
                  ->orWhere('batch_id', $student->batch_id);
        })->count();
        
        $totalExams = Exam::count();
        $this->info("Student has access to {$accessibleExams} out of {$totalExams} exams.");
        
        // Option 3: Assign student to all batches
        if ($this->confirm('Do you want to assign this student to all batches? (This will give access to all exams)', true)) {
            $batches = \App\Models\Batch::pluck('id')->toArray();
            
            // Check if Student model has a many-to-many relationship with Batch
            // If not, we'll need to update the student's batch_id to give access
            
            $this->info("\nNote: The Student model uses a single batch_id field.");
            $this->info("To grant access to all exams, we have two options:");
            $this->info("1. Remove batch restrictions from all exams (makes exams available to everyone)");
            $this->info("2. Manually update each exam's batch_id to match the student's batch");
            
            if ($this->confirm('Would you like to set all exams to batch_id = ' . $student->batch_id . '?', false)) {
                $count = Exam::update(['batch_id' => $student->batch_id]);
                $this->info("Updated {$count} exams to batch_id = {$student->batch_id}");
                $this->info("Student now has access to all exams.");
                return 0;
            }
        }
        
        $this->info("\nNo changes made. Student still has access to {$accessibleExams} exams.");
        return 0;
    }
}

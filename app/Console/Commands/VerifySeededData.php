<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
    Course, CourseVideo, Batch, Student, Teacher, ClassSchedule,
    Attendance, Exam, ExamResult, Invoice, Payment, ParentModel
};

class VerifySeededData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:verify-seeded-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify that all data has been properly seeded';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verifying Seeded Data...');
        $this->newLine();

        $data = [
            'Courses' => Course::count(),
            'Course Videos' => CourseVideo::count(),
            'Batches' => Batch::count(),
            'Students' => Student::count(),
            'Teachers' => Teacher::count(),
            'Class Schedules' => ClassSchedule::count(),
            'Attendance Records' => Attendance::count(),
            'Exams' => Exam::count(),
            'Exam Results' => ExamResult::count(),
            'Invoices' => Invoice::count(),
            'Payments' => Payment::count(),
            'Parent Accounts' => ParentModel::count(),
        ];

        // Display counts
        $this->table(
            ['Data Type', 'Count'],
            collect($data)->map(fn($count, $type) => [$type, $count])->toArray()
        );

        $this->newLine();

        // Check for potential issues
        $issues = [];

        if (Course::count() === 0) {
            $issues[] = '❌ No courses found. Run CourseSeeder first.';
        }

        if (CourseVideo::count() === 0) {
            $issues[] = '⚠️  No course videos found. Students cannot access video content.';
        }

        if (Student::count() === 0) {
            $issues[] = '❌ No students found. Run StudentManagementSeeder first.';
        }

        if (ClassSchedule::count() === 0) {
            $issues[] = '⚠️  No class schedules found. Students cannot view their timetable.';
        }

        if (Attendance::count() === 0) {
            $issues[] = '⚠️  No attendance records found. Attendance reports will be empty.';
        }

        if (ExamResult::count() === 0) {
            $issues[] = '⚠️  No exam results found. Students cannot view their results.';
        }

        if (Invoice::count() === 0) {
            $issues[] = '⚠️  No invoices found. Fee management will be empty.';
        }

        if (ParentModel::count() === 0) {
            $issues[] = '⚠️  No parent accounts found. Parent portal will be empty.';
        }

        // Display issues or success
        if (empty($issues)) {
            $this->info('✅ All data has been properly seeded!');
            $this->newLine();
            
            // Additional statistics
            $this->info('📊 Additional Statistics:');
            $this->line('  • Average videos per course: ' . round(CourseVideo::count() / max(Course::count(), 1), 1));
            $this->line('  • Average schedules per batch: ' . round(ClassSchedule::count() / max(Batch::count(), 1), 1));
            $this->line('  • Average attendance per student: ' . round(Attendance::count() / max(Student::count(), 1), 1));
            $this->line('  • Average results per student: ' . round(ExamResult::count() / max(Student::count(), 1), 1));
            $this->line('  • Average invoices per student: ' . round(Invoice::count() / max(Student::count(), 1), 1));
            $this->line('  • Payment completion rate: ' . round((Payment::count() / max(Invoice::count(), 1)) * 100, 1) . '%');
            
            $this->newLine();
            $this->info('🎉 Your LMS is ready to use!');
        } else {
            $this->warn('⚠️  Some issues found:');
            foreach ($issues as $issue) {
                $this->line('  ' . $issue);
            }
            $this->newLine();
            $this->info('💡 Run: php artisan migrate:fresh --seed');
        }

        return 0;
    }
}

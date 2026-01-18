<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use App\Models\Batch;
use App\Models\CourseMaterial;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ClassSchedule;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StudentPortalDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a student user
        $studentUser = User::where('email', 'student@demo.com')->first();
        if (!$studentUser) {
            $studentUser = User::create([
                'name' => 'Demo Student',
                'email' => 'student@demo.com',
                'password' => bcrypt('password'),
            ]);
            $studentUser->roles()->attach(\App\Models\Role::where('slug', 'student')->first());
        }

        // Get or create student record
        $student = Student::where('user_id', $studentUser->id)->first();
        if (!$student) {
            $batch = Batch::first();
            $student = Student::create([
                'user_id' => $studentUser->id,
                'registration_no' => 'STU-2026-0001',
                'batch_id' => $batch?->id,
                'phone' => '01700123456',
                'guardian_phone' => '01900123456',
                'status' => 'active',
            ]);
        }

        // Seed Course Materials
        $this->seedCourseMaterials($student);

        // Seed Exam Results
        $this->seedExamResults($student);

        // Seed Class Schedule
        $this->seedClassSchedule($student);

        // Seed Payments
        $this->seedPayments($student);

        // Seed Attendance
        $this->seedAttendance($student);

        $this->command->info('Student portal data seeded successfully!');
    }

    private function seedCourseMaterials($student)
    {
        $batch = $student->batch;
        if (!$batch) return;

        $materials = [
            [
                'title' => 'Introduction to Physics - Chapter 1',
                'description' => 'Basic concepts of mechanics and motion',
                'type' => 'pdf',
                'file_path' => 'materials/physics-ch1.pdf',
            ],
            [
                'title' => 'Mathematics Formula Sheet',
                'description' => 'Important formulas for calculus and algebra',
                'type' => 'pdf',
                'file_path' => 'materials/math-formulas.pdf',
            ],
            [
                'title' => 'Chemistry Lab Video - Experiment 1',
                'description' => 'Demonstration of acid-base titration',
                'type' => 'video',
                'file_path' => 'https://www.youtube.com/watch?v=example',
            ],
            [
                'title' => 'English Grammar Guide',
                'description' => 'Comprehensive guide to English grammar rules',
                'type' => 'pdf',
                'file_path' => 'materials/english-grammar.pdf',
            ],
            [
                'title' => 'Biology Notes - Cell Structure',
                'description' => 'Detailed notes on cell biology',
                'type' => 'pdf',
                'file_path' => 'materials/biology-cells.pdf',
            ],
        ];

        foreach ($materials as $material) {
            CourseMaterial::updateOrCreate(
                ['title' => $material['title']],
                array_merge($material, [
                    'batch_id' => $batch->id,
                    'course_id' => $batch->course_id,
                ])
            );
        }
    }

    private function seedExamResults($student)
    {
        $batch = $student->batch;
        if (!$batch) return;

        // Create exams
        $exams = [
            [
                'title' => 'Mid-term Examination - Physics',
                'type' => 'mcq',
                'total_marks' => 100,
                'pass_marks' => 40,
                'duration' => 120,
                'exam_date' => Carbon::now()->subDays(30),
            ],
            [
                'title' => 'Weekly Test - Mathematics',
                'type' => 'mcq',
                'total_marks' => 50,
                'pass_marks' => 20,
                'duration' => 60,
                'exam_date' => Carbon::now()->subDays(20),
            ],
            [
                'title' => 'Monthly Assessment - Chemistry',
                'type' => 'cq',
                'total_marks' => 75,
                'pass_marks' => 30,
                'duration' => 90,
                'exam_date' => Carbon::now()->subDays(15),
            ],
            [
                'title' => 'Final Examination - English',
                'type' => 'mcq',
                'total_marks' => 100,
                'pass_marks' => 40,
                'duration' => 120,
                'exam_date' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Quiz - Biology',
                'type' => 'mcq',
                'total_marks' => 30,
                'pass_marks' => 12,
                'duration' => 30,
                'exam_date' => Carbon::now()->subDays(5),
            ],
        ];

        $scores = [85, 72, 68, 90, 28]; // Last one is failing

        foreach ($exams as $index => $examData) {
            $exam = Exam::updateOrCreate(
                ['title' => $examData['title']],
                array_merge($examData, [
                    'batch_id' => $batch->id,
                    'course_id' => $batch->course_id,
                    'status' => 'published',
                ])
            );

            $obtainedMarks = $scores[$index];
            $totalMarks = $examData['total_marks'];
            $percentage = ($obtainedMarks / $totalMarks) * 100;
            
            // Calculate grade
            if ($percentage >= 80) $grade = 'A+';
            elseif ($percentage >= 70) $grade = 'A';
            elseif ($percentage >= 60) $grade = 'B';
            elseif ($percentage >= 50) $grade = 'C';
            elseif ($percentage >= 40) $grade = 'D';
            else $grade = 'F';

            ExamResult::updateOrCreate(
                [
                    'exam_id' => $exam->id,
                    'student_id' => $student->id,
                ],
                [
                    'subject_name' => $exam->title,
                    'marks' => $obtainedMarks,
                    'obtained_marks' => $obtainedMarks,
                    'total_marks' => $totalMarks,
                    'grade' => $grade,
                ]
            );
        }
    }

    private function seedClassSchedule($student)
    {
        $batch = $student->batch;
        if (!$batch) return;

        $teacher = Teacher::first();

        $schedules = [
            // Sunday
            ['day' => 0, 'subject' => 'Physics', 'start' => '09:00', 'end' => '10:30', 'room' => 'Room 101'],
            ['day' => 0, 'subject' => 'Mathematics', 'start' => '11:00', 'end' => '12:30', 'room' => 'Room 102'],
            
            // Monday
            ['day' => 1, 'subject' => 'Chemistry', 'start' => '09:00', 'end' => '10:30', 'room' => 'Lab 1'],
            ['day' => 1, 'subject' => 'English', 'start' => '11:00', 'end' => '12:30', 'room' => 'Room 103'],
            
            // Tuesday
            ['day' => 2, 'subject' => 'Biology', 'start' => '09:00', 'end' => '10:30', 'room' => 'Lab 2'],
            ['day' => 2, 'subject' => 'Physics', 'start' => '11:00', 'end' => '12:30', 'room' => 'Room 101'],
            
            // Wednesday
            ['day' => 3, 'subject' => 'Mathematics', 'start' => '09:00', 'end' => '10:30', 'room' => 'Room 102'],
            ['day' => 3, 'subject' => 'Chemistry', 'start' => '11:00', 'end' => '12:30', 'room' => 'Lab 1'],
            
            // Thursday
            ['day' => 4, 'subject' => 'English', 'start' => '09:00', 'end' => '10:30', 'room' => 'Room 103'],
            ['day' => 4, 'subject' => 'Biology', 'start' => '11:00', 'end' => '12:30', 'room' => 'Lab 2'],
        ];

        foreach ($schedules as $schedule) {
            ClassSchedule::updateOrCreate(
                [
                    'batch_id' => $batch->id,
                    'day_of_week' => $schedule['day'],
                    'start_time' => $schedule['start'],
                ],
                [
                    'subject' => $schedule['subject'],
                    'end_time' => $schedule['end'],
                    'room' => $schedule['room'],
                    'teacher_id' => $teacher?->id,
                ]
            );
        }
    }

    private function seedPayments($student)
    {
        $batch = $student->batch;
        if (!$batch) return;

        $payments = [
            [
                'amount' => 5000,
                'payment_method' => 'cash',
                'status' => 'completed',
                'payment_date' => Carbon::now()->subMonths(3),
                'receipt_number' => 'RCP-2026-0001',
            ],
            [
                'amount' => 5000,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'payment_date' => Carbon::now()->subMonths(2),
                'receipt_number' => 'RCP-2026-0002',
            ],
            [
                'amount' => 5000,
                'payment_method' => 'bkash',
                'status' => 'completed',
                'payment_date' => Carbon::now()->subMonth(),
                'receipt_number' => 'RCP-2026-0003',
            ],
            [
                'amount' => 2500,
                'payment_method' => 'cash',
                'status' => 'pending',
                'payment_date' => null,
                'receipt_number' => null,
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create(array_merge($payment, [
                'student_id' => $student->id,
                'batch_id' => $batch->id,
            ]));
        }
    }

    private function seedAttendance($student)
    {
        $batch = $student->batch;
        if (!$batch) return;

        $attendanceData = [];
        
        // Create attendance for last 30 days
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip weekends (Friday and Saturday in Bangladesh)
            if ($date->dayOfWeek == Carbon::FRIDAY || $date->dayOfWeek == Carbon::SATURDAY) {
                continue;
            }

            // 85% attendance rate
            $status = ($i % 7 == 0) ? 'absent' : 'present';

            $attendanceData[] = [
                'student_id' => $student->id,
                'batch_id' => $batch->id,
                'date' => $date->format('Y-m-d'),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Bulk insert
        if (!empty($attendanceData)) {
            Attendance::insert($attendanceData);
        }
    }
}

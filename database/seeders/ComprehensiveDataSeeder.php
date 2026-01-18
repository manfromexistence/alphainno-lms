<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Course, CourseVideo, Batch, Student, Teacher, ClassSchedule,
    Attendance, Exam, Question, ExamResult, ExamAttempt,
    Invoice, Payment, ParentModel
};
use Illuminate\Support\Facades\Hash;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run comprehensive database seeding for all admin panel pages.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting comprehensive data seeding...');
        
        // Seed in order of dependencies
        $this->seedCourseVideos();
        $this->seedClassSchedules();
        $this->seedAttendanceRecords();
        $this->seedExamsAndResults();
        $this->seedInvoicesAndPayments();
        $this->seedParentAccounts();
        
        $this->command->info('✅ Comprehensive seeding completed successfully!');
    }

    /**
     * Seed course videos with real educational content from YouTube
     */
    private function seedCourseVideos(): void
    {
        $this->command->info('📹 Seeding course videos...');
        
        // Real free educational YouTube videos
        $educationalVideos = [
            // Programming & Computer Science
            ['id' => 'RF_eOpCLayA', 'title' => 'Python Programming - Complete Course', 'duration' => 14400],
            ['id' => 'PkZNo7MFNFg', 'title' => 'JavaScript Full Course for Beginners', 'duration' => 11700],
            ['id' => 'pQN-pnXPaVg', 'title' => 'HTML Tutorial - Build a Website', 'duration' => 7200],
            ['id' => 'yfoY53QXEnI', 'title' => 'CSS Complete Course - Zero to Hero', 'duration' => 10800],
            ['id' => 'RGOj5yH7evk', 'title' => 'Git and GitHub for Beginners', 'duration' => 3900],
            
            // Mathematics
            ['id' => 'WUvTyaaNkzM', 'title' => 'Calculus 1 - Full College Course', 'duration' => 21600],
            ['id' => 'LwCRRUa8yTU', 'title' => 'Algebra - Basic Introduction', 'duration' => 9000],
            ['id' => 'fNk_zzaMoSs', 'title' => 'Linear Algebra Full Course', 'duration' => 18000],
            
            // Science
            ['id' => 'cbJOAiz0WN8', 'title' => 'Physics - Introduction to Mechanics', 'duration' => 12600],
            ['id' => 'FSyAehMdpyI', 'title' => 'Chemistry - Complete Course', 'duration' => 16200],
            ['id' => 'QnQe0xW_JY4', 'title' => 'Biology - Cell Structure and Function', 'duration' => 5400],
            
            // English & Language
            ['id' => 'Rjd4_Wn0kxI', 'title' => 'English Grammar Course for Beginners', 'duration' => 7200],
            ['id' => 'ub3HcgmFmJY', 'title' => 'English Vocabulary Building', 'duration' => 3600],
            
            // Additional Educational Content
            ['id' => 'nu_pCVPKzTk', 'title' => 'React Tutorial for Beginners', 'duration' => 8100],
            ['id' => 'SLpUKAGnm-g', 'title' => 'SQL Database Course', 'duration' => 14400],
            ['id' => 'Wm6CUkswsNw', 'title' => 'Data Structures and Algorithms', 'duration' => 36000],
        ];
        
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Skipping video seeding.');
            return;
        }
        
        $videoCount = 0;
        
        foreach ($courses as $course) {
            // Add 5-8 videos per course
            $numVideos = rand(5, 8);
            
            for ($i = 1; $i <= $numVideos; $i++) {
                $video = $educationalVideos[array_rand($educationalVideos)];
                
                CourseVideo::create([
                    'course_id' => $course->id,
                    'title' => "Lesson {$i}: " . $video['title'],
                    'description' => "This comprehensive lesson covers important concepts in {$course->name}. Watch carefully and take notes for better understanding.",
                    'video_type' => 'youtube',
                    'external_id' => $video['id'],
                    'duration' => $video['duration'],
                    'order' => $i,
                    'is_preview' => $i === 1, // First video is preview
                ]);
                
                $videoCount++;
            }
        }
        
        $this->command->info("✓ Created {$videoCount} course videos");
    }

    /**
     * Seed class schedules for all batches
     */
    private function seedClassSchedules(): void
    {
        $this->command->info('📅 Seeding class schedules...');
        
        $batches = Batch::all();
        $teachers = Teacher::all();
        
        if ($batches->isEmpty()) {
            $this->command->warn('No batches found. Skipping schedule seeding.');
            return;
        }
        
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        $subjects = [
            'Mathematics', 'English', 'Physics', 'Chemistry', 'Biology',
            'Computer Science', 'Bangla', 'Islamic Studies', 'History',
            'Geography', 'Economics', 'Accounting'
        ];
        
        $timeSlots = [
            ['start' => '08:00:00', 'end' => '09:30:00'],
            ['start' => '09:45:00', 'end' => '11:15:00'],
            ['start' => '11:30:00', 'end' => '13:00:00'],
            ['start' => '14:00:00', 'end' => '15:30:00'],
            ['start' => '15:45:00', 'end' => '17:15:00'],
            ['start' => '17:30:00', 'end' => '19:00:00'],
        ];
        
        $rooms = ['Room 101', 'Room 102', 'Room 103', 'Room 201', 'Room 202', 'Lab 1', 'Lab 2', 'Computer Lab'];
        
        $scheduleCount = 0;
        
        foreach ($batches as $batch) {
            // Create 4-6 classes per week
            $classesPerWeek = rand(4, 6);
            $usedDays = [];
            
            for ($i = 0; $i < $classesPerWeek; $i++) {
                $availableDays = array_diff($days, $usedDays);
                if (empty($availableDays)) break;
                
                $day = $availableDays[array_rand($availableDays)];
                $usedDays[] = $day;
                
                $timeSlot = $timeSlots[array_rand($timeSlots)];
                $subject = $subjects[array_rand($subjects)];
                $teacherId = $teachers->isNotEmpty() ? $teachers->random()->id : null;
                $room = $rooms[array_rand($rooms)];
                
                ClassSchedule::create([
                    'batch_id' => $batch->id,
                    'teacher_id' => $teacherId,
                    'subject' => $subject,
                    'day_of_week' => $day,
                    'start_time' => $timeSlot['start'],
                    'end_time' => $timeSlot['end'],
                    'room' => $room,
                ]);
                
                $scheduleCount++;
            }
        }
        
        $this->command->info("✓ Created {$scheduleCount} class schedules");
    }

    /**
     * Seed attendance records for students
     */
    private function seedAttendanceRecords(): void
    {
        $this->command->info('📊 Seeding attendance records...');
        
        $students = Student::with('batch')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Skipping attendance seeding.');
            return;
        }
        
        $attendanceCount = 0;
        $statuses = ['present', 'absent', 'late', 'excused'];
        $weights = [70, 10, 15, 5]; // Weighted probability
        
        foreach ($students as $student) {
            // Create attendance for last 60 days
            for ($i = 60; $i >= 0; $i--) {
                $date = now()->subDays($i);
                
                // Skip weekends (Friday)
                if ($date->dayOfWeek === 5) continue;
                
                // Random status with weighted probability
                $status = $this->weightedRandom($statuses, $weights);
                
                Attendance::create([
                    'student_id' => $student->id,
                    'batch_id' => $student->batch_id,
                    'date' => $date,
                    'status' => $status,
                ]);
                
                $attendanceCount++;
            }
        }
        
        $this->command->info("✓ Created {$attendanceCount} attendance records");
    }

    /**
     * Seed exams, questions, and results
     */
    private function seedExamsAndResults(): void
    {
        $this->command->info('📝 Seeding exams and results...');
        
        $batches = Batch::with('course')->get();
        
        if ($batches->isEmpty()) {
            $this->command->warn('No batches found. Skipping exam seeding.');
            return;
        }
        
        $examCount = 0;
        $resultCount = 0;
        
        foreach ($batches as $batch) {
            // Create 3-5 exams per batch
            $numExams = rand(3, 5);
            
            for ($i = 1; $i <= $numExams; $i++) {
                $type = rand(0, 1) ? 'mcq' : 'cq';
                $isPast = rand(1, 100) <= 70; // 70% past exams
                
                $exam = Exam::create([
                    'course_id' => $batch->course_id,
                    'batch_id' => $batch->id,
                    'title' => $batch->course->name . ' - ' . $this->getExamName($i),
                    'type' => $type,
                    'duration_minutes' => $type === 'mcq' ? rand(45, 90) : rand(90, 180),
                    'total_marks' => $type === 'mcq' ? 50 : 100,
                    'pass_marks' => $type === 'mcq' ? 20 : 40,
                    'start_time' => $isPast ? now()->subDays(rand(5, 60)) : now()->addDays(rand(1, 30)),
                    'end_time' => $isPast ? now()->subDays(rand(5, 60))->addHours(2) : now()->addDays(rand(1, 30))->addHours(2),
                    'status' => 'active',
                    'instructions' => 'Read all questions carefully. Answer to the best of your ability. Good luck!',
                ]);
                
                // Add questions
                $this->createQuestionsForExam($exam, $type);
                
                // Create results for past exams
                if ($isPast) {
                    $students = Student::where('batch_id', $batch->id)->get();
                    
                    foreach ($students as $student) {
                        // 85% students took the exam
                        if (rand(1, 100) <= 85) {
                            $obtainedMarks = $this->generateRealisticMarks($exam->total_marks, $exam->pass_marks);
                            
                            ExamAttempt::create([
                                'exam_id' => $exam->id,
                                'student_id' => $student->id,
                                'started_at' => $exam->start_time,
                                'submitted_at' => $exam->start_time->addMinutes($exam->duration_minutes - rand(5, 20)),
                                'answers' => json_encode([]),
                                'status' => 'submitted',
                            ]);
                            
                            ExamResult::create([
                                'exam_id' => $exam->id,
                                'student_id' => $student->id,
                                'subject_name' => $exam->title,
                                'marks' => $obtainedMarks,
                                'obtained_marks' => $obtainedMarks,
                                'total_marks' => $exam->total_marks,
                                'grade' => $this->calculateGrade($obtainedMarks, $exam->total_marks),
                                'feedback' => $this->generateFeedback($obtainedMarks, $exam->total_marks),
                            ]);
                            
                            $resultCount++;
                        }
                    }
                }
                
                $examCount++;
            }
        }
        
        $this->command->info("✓ Created {$examCount} exams and {$resultCount} results");
    }

    /**
     * Seed invoices and payments
     */
    private function seedInvoicesAndPayments(): void
    {
        $this->command->info('💰 Seeding invoices and payments...');
        
        $students = Student::all();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Skipping invoice seeding.');
            return;
        }
        
        $invoiceCount = 0;
        $paymentCount = 0;
        
        foreach ($students as $student) {
            // Create 2-4 invoices per student
            $numInvoices = rand(2, 4);
            
            for ($i = 1; $i <= $numInvoices; $i++) {
                $amount = rand(3000, 15000);
                $isPaid = rand(1, 100) <= 65; // 65% paid
                $isOverdue = !$isPaid && rand(1, 100) <= 30; // 30% of unpaid are overdue
                
                $issueDate = now()->subMonths($numInvoices - $i + 1);
                $dueDate = $isOverdue 
                    ? $issueDate->copy()->addDays(rand(15, 30))
                    : $issueDate->copy()->addDays(rand(30, 60));
                
                $invoice = Invoice::create([
                    'student_id' => $student->id,
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($invoiceCount + 1, 5, '0', STR_PAD_LEFT),
                    'amount' => $amount,
                    'due_date' => $dueDate,
                    'status' => $isPaid ? 'paid' : ($isOverdue ? 'overdue' : 'pending'),
                    'items' => json_encode([
                        [
                            'description' => $this->getInvoiceDescription($i),
                            'amount' => $amount,
                        ]
                    ]),
                ]);
                
                $invoiceCount++;
                
                // Create payment if paid
                if ($isPaid) {
                    $paymentDate = $issueDate->copy()->addDays(rand(1, 25));
                    
                    Payment::create([
                        'student_id' => $student->id,
                        'invoice_id' => $invoice->id,
                        'amount' => $amount,
                        'payment_date' => $paymentDate,
                        'payment_method' => $this->getRandomPaymentMethod(),
                        'transaction_id' => 'TXN-' . strtoupper(substr(md5(uniqid()), 0, 10)),
                        'status' => 'completed',
                        'notes' => 'Payment received successfully',
                    ]);
                    
                    $paymentCount++;
                }
            }
        }
        
        $this->command->info("✓ Created {$invoiceCount} invoices and {$paymentCount} payments");
    }

    /**
     * Seed parent accounts and relationships
     */
    private function seedParentAccounts(): void
    {
        $this->command->info('👨‍👩‍👧‍👦 Seeding parent accounts...');
        
        $students = Student::with('user')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Skipping parent seeding.');
            return;
        }
        
        $parentCount = 0;
        $relationshipCount = 0;
        $createdParents = [];
        
        // First, create the main parent@gmail.com account
        $mainParent = ParentModel::updateOrCreate(
            ['email' => 'parent@gmail.com'],
            [
                'name' => 'Demo Parent',
                'phone' => '01700000001',
                'password' => Hash::make('password'),
                'notification_preferences' => [
                    'email_notifications' => true,
                    'sms_notifications' => true,
                    'exam_alerts' => true,
                    'attendance_alerts' => true,
                    'payment_reminders' => true,
                ],
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );
        
        $createdParents[] = $mainParent;
        $parentCount++;
        
        // Link the main parent to the first 2 students (as children)
        $firstStudents = $students->take(2);
        foreach ($firstStudents as $index => $student) {
            $relationshipType = $index === 0 ? 'father' : 'mother';
            
            // Check if relationship already exists
            $exists = \DB::table('parent_student')
                ->where('parent_id', $mainParent->id)
                ->where('student_id', $student->id)
                ->exists();
            
            if (!$exists) {
                $mainParent->students()->attach($student->id, [
                    'relationship_type' => $relationshipType,
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => now(),
                ]);
                
                $relationshipCount++;
            }
        }
        
        // Continue with other students
        foreach ($students->skip(2) as $index => $student) {
            // 80% chance student has a parent account
            if (rand(1, 100) <= 80) {
                // 25% chance to reuse existing parent (siblings)
                if (rand(1, 100) <= 25 && count($createdParents) > 1) {
                    $parent = $createdParents[array_rand($createdParents)];
                } else {
                    // Create new parent
                    $parent = ParentModel::create([
                        'name' => $this->generateParentName(),
                        'email' => 'parent' . ($parentCount + 1) . '@example.com',
                        'phone' => '017' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                        'password' => Hash::make('password'),
                        'notification_preferences' => [
                            'email_notifications' => true,
                            'sms_notifications' => true,
                            'exam_alerts' => true,
                            'attendance_alerts' => true,
                            'payment_reminders' => true,
                        ],
                        'email_verified_at' => now(),
                        'phone_verified_at' => now(),
                    ]);
                    
                    $createdParents[] = $parent;
                    $parentCount++;
                }
                
                // Link parent to student
                $relationshipType = $this->getRandomRelationship();
                
                // Check if relationship already exists
                $exists = \DB::table('parent_student')
                    ->where('parent_id', $parent->id)
                    ->where('student_id', $student->id)
                    ->exists();
                
                if (!$exists) {
                    $parent->students()->attach($student->id, [
                        'relationship_type' => $relationshipType,
                        'status' => 'approved',
                        'approved_by' => 1,
                        'approved_at' => now(),
                    ]);
                    
                    $relationshipCount++;
                }
            }
        }
        
        $this->command->info("✓ Created {$parentCount} parent accounts with {$relationshipCount} relationships");
    }

    // Helper methods
    
    private function weightedRandom(array $values, array $weights): string
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        
        $sum = 0;
        foreach ($values as $i => $value) {
            $sum += $weights[$i];
            if ($random <= $sum) {
                return $value;
            }
        }
        
        return $values[0];
    }

    private function getExamName(int $number): string
    {
        $names = ['First Term', 'Mid Term', 'Final Term', 'Monthly Test', 'Unit Test'];
        return $names[($number - 1) % count($names)];
    }

    private function createQuestionsForExam(Exam $exam, string $type): void
    {
        $numQuestions = $type === 'mcq' ? 10 : 5;
        $marksPerQuestion = $exam->total_marks / $numQuestions;
        
        for ($q = 1; $q <= $numQuestions; $q++) {
            $questionData = [
                'exam_id' => $exam->id,
                'question_text' => "Question {$q}: " . $this->generateQuestionText($type),
                'type' => $type,
                'marks' => $marksPerQuestion,
                'order' => $q,
            ];
            
            if ($type === 'mcq') {
                $questionData['options'] = [
                    'A) ' . $this->generateOption(),
                    'B) ' . $this->generateOption(),
                    'C) ' . $this->generateOption(),
                    'D) ' . $this->generateOption(),
                ];
                $questionData['correct_answer'] = $questionData['options'][0];
            }
            
            Question::create($questionData);
        }
    }

    private function generateQuestionText(string $type): string
    {
        $questions = [
            'What is the primary concept discussed in this topic?',
            'Explain the fundamental principles of this subject.',
            'How does this concept apply in real-world scenarios?',
            'What are the key differences between these approaches?',
            'Describe the process and methodology involved.',
        ];
        
        return $questions[array_rand($questions)];
    }

    private function generateOption(): string
    {
        $options = [
            'The correct answer based on theory',
            'An alternative explanation',
            'A common misconception',
            'The traditional approach',
        ];
        
        return $options[array_rand($options)];
    }

    private function generateRealisticMarks(int $total, int $pass): int
    {
        // Generate marks with realistic distribution
        $rand = rand(1, 100);
        
        if ($rand <= 10) {
            // 10% fail
            return rand(0, $pass - 1);
        } elseif ($rand <= 40) {
            // 30% average (pass to 70%)
            return rand($pass, (int)($total * 0.7));
        } elseif ($rand <= 80) {
            // 40% good (70% to 85%)
            return rand((int)($total * 0.7), (int)($total * 0.85));
        } else {
            // 20% excellent (85% to 100%)
            return rand((int)($total * 0.85), $total);
        }
    }

    private function calculateGrade(int $obtained, int $total): string
    {
        $percentage = ($obtained / $total) * 100;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'A-';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    private function generateFeedback(int $obtained, int $total): string
    {
        $percentage = ($obtained / $total) * 100;
        
        if ($percentage >= 90) return 'Outstanding performance! Keep up the excellent work.';
        if ($percentage >= 80) return 'Excellent work! You have a strong grasp of the material.';
        if ($percentage >= 70) return 'Good job! Continue to build on this foundation.';
        if ($percentage >= 60) return 'Satisfactory performance. Focus on improving weak areas.';
        if ($percentage >= 50) return 'Average performance. More practice is recommended.';
        if ($percentage >= 40) return 'Needs improvement. Please review the material carefully.';
        return 'Requires significant improvement. Consider additional tutoring.';
    }

    private function getInvoiceDescription(int $number): string
    {
        $descriptions = [
            'Monthly Tuition Fee',
            'Admission Fee',
            'Exam Fee',
            'Course Materials Fee',
            'Library and Lab Fee',
            'Sports and Activities Fee',
        ];
        
        return $descriptions[($number - 1) % count($descriptions)];
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['cash', 'bank_transfer', 'card', 'mobile_banking', 'bkash', 'nagad'];
        return $methods[array_rand($methods)];
    }

    private function getRandomRelationship(): string
    {
        $relationships = ['father', 'mother', 'guardian'];
        $weights = [40, 40, 20];
        return $this->weightedRandom($relationships, $weights);
    }

    private function generateParentName(): string
    {
        $firstNames = [
            'Mohammad', 'Abdul', 'Ahmed', 'Karim', 'Rahim', 'Jamal',
            'Fatima', 'Ayesha', 'Nasreen', 'Sultana', 'Begum', 'Khatun'
        ];
        $lastNames = [
            'Rahman', 'Ahmed', 'Khan', 'Islam', 'Hossain', 'Ali',
            'Begum', 'Akter', 'Khatun', 'Sultana'
        ];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}

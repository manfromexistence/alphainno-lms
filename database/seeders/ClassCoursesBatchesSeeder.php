<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Student;
use App\Models\User;

class ClassCoursesBatchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting ClassCoursesBatchesSeeder...');
        
        // Sample courses for different classes with unique codes
        $coursesData = [
            // Class 1-5 (Primary)
            ['class' => 1, 'name' => 'English Foundation', 'code' => 'CLS1-ENG-001', 'price' => 2000, 'category' => 'Language', 'level' => 'beginner'],
            ['class' => 1, 'name' => 'Mathematics Basics', 'code' => 'CLS1-MATH-001', 'price' => 2000, 'category' => 'Mathematics', 'level' => 'beginner'],
            ['class' => 2, 'name' => 'English Level 2', 'code' => 'CLS2-ENG-001', 'price' => 2200, 'category' => 'Language', 'level' => 'beginner'],
            ['class' => 2, 'name' => 'Mathematics Level 2', 'code' => 'CLS2-MATH-001', 'price' => 2200, 'category' => 'Mathematics', 'level' => 'beginner'],
            ['class' => 3, 'name' => 'English Level 3', 'code' => 'CLS3-ENG-001', 'price' => 2500, 'category' => 'Language', 'level' => 'beginner'],
            ['class' => 3, 'name' => 'Science Basics', 'code' => 'CLS3-SCI-001', 'price' => 2500, 'category' => 'Science', 'level' => 'beginner'],
            ['class' => 4, 'name' => 'English Level 4', 'code' => 'CLS4-ENG-001', 'price' => 2800, 'category' => 'Language', 'level' => 'intermediate'],
            ['class' => 4, 'name' => 'General Science', 'code' => 'CLS4-SCI-001', 'price' => 2800, 'category' => 'Science', 'level' => 'intermediate'],
            ['class' => 5, 'name' => 'English Level 5', 'code' => 'CLS5-ENG-001', 'price' => 3000, 'category' => 'Language', 'level' => 'intermediate'],
            ['class' => 5, 'name' => 'Mathematics Level 5', 'code' => 'CLS5-MATH-001', 'price' => 3000, 'category' => 'Mathematics', 'level' => 'intermediate'],
            
            // Class 6-8 (Junior Secondary)
            ['class' => 6, 'name' => 'English Grammar & Composition', 'code' => 'CLS6-ENG-001', 'price' => 3500, 'category' => 'Language', 'level' => 'intermediate'],
            ['class' => 6, 'name' => 'General Mathematics', 'code' => 'CLS6-MATH-001', 'price' => 3500, 'category' => 'Mathematics', 'level' => 'intermediate'],
            ['class' => 6, 'name' => 'General Science', 'code' => 'CLS6-SCI-001', 'price' => 3500, 'category' => 'Science', 'level' => 'intermediate'],
            ['class' => 7, 'name' => 'English Literature', 'code' => 'CLS7-ENG-001', 'price' => 4000, 'category' => 'Language', 'level' => 'intermediate'],
            ['class' => 7, 'name' => 'Algebra & Geometry', 'code' => 'CLS7-MATH-001', 'price' => 4000, 'category' => 'Mathematics', 'level' => 'intermediate'],
            ['class' => 7, 'name' => 'Physics & Chemistry', 'code' => 'CLS7-SCI-001', 'price' => 4000, 'category' => 'Science', 'level' => 'intermediate'],
            ['class' => 8, 'name' => 'Advanced English', 'code' => 'CLS8-ENG-001', 'price' => 4500, 'category' => 'Language', 'level' => 'intermediate'],
            ['class' => 8, 'name' => 'Advanced Mathematics', 'code' => 'CLS8-MATH-001', 'price' => 4500, 'category' => 'Mathematics', 'level' => 'intermediate'],
            ['class' => 8, 'name' => 'Biology & Chemistry', 'code' => 'CLS8-SCI-001', 'price' => 4500, 'category' => 'Science', 'level' => 'intermediate'],
            
            // Class 9-10 (SSC)
            ['class' => 9, 'name' => 'SSC English First Paper', 'code' => 'CLS9-ENG1-001', 'price' => 5000, 'category' => 'Language', 'level' => 'advanced'],
            ['class' => 9, 'name' => 'SSC Mathematics', 'code' => 'CLS9-MATH-001', 'price' => 5000, 'category' => 'Mathematics', 'level' => 'advanced'],
            ['class' => 9, 'name' => 'SSC Physics', 'code' => 'CLS9-PHY-001', 'price' => 5000, 'category' => 'Science', 'level' => 'advanced'],
            ['class' => 9, 'name' => 'SSC Chemistry', 'code' => 'CLS9-CHEM-001', 'price' => 5000, 'category' => 'Science', 'level' => 'advanced'],
            ['class' => 10, 'name' => 'SSC English Second Paper', 'code' => 'CLS10-ENG2-001', 'price' => 5500, 'category' => 'Language', 'level' => 'advanced'],
            ['class' => 10, 'name' => 'SSC Higher Mathematics', 'code' => 'CLS10-HMATH-001', 'price' => 5500, 'category' => 'Mathematics', 'level' => 'advanced'],
            ['class' => 10, 'name' => 'SSC Biology', 'code' => 'CLS10-BIO-001', 'price' => 5500, 'category' => 'Science', 'level' => 'advanced'],
            
            // Class 11-12 (HSC)
            ['class' => 11, 'name' => 'HSC English First Paper', 'code' => 'CLS11-ENG1-001', 'price' => 6000, 'category' => 'Language', 'level' => 'advanced'],
            ['class' => 11, 'name' => 'HSC Physics First Paper', 'code' => 'CLS11-PHY1-001', 'price' => 6000, 'category' => 'Science', 'level' => 'advanced'],
            ['class' => 11, 'name' => 'HSC Chemistry First Paper', 'code' => 'CLS11-CHEM1-001', 'price' => 6000, 'category' => 'Science', 'level' => 'advanced'],
            ['class' => 11, 'name' => 'HSC Mathematics First Paper', 'code' => 'CLS11-MATH1-001', 'price' => 6000, 'category' => 'Mathematics', 'level' => 'advanced'],
            ['class' => 12, 'name' => 'HSC English Second Paper', 'code' => 'CLS12-ENG2-001', 'price' => 6500, 'category' => 'Language', 'level' => 'advanced'],
            ['class' => 12, 'name' => 'HSC Physics Second Paper', 'code' => 'CLS12-PHY2-001', 'price' => 6500, 'category' => 'Science', 'level' => 'advanced'],
            ['class' => 12, 'name' => 'HSC Chemistry Second Paper', 'code' => 'CLS12-CHEM2-001', 'price' => 6500, 'category' => 'Science', 'level' => 'advanced'],
            ['class' => 12, 'name' => 'HSC Higher Mathematics', 'code' => 'CLS12-HMATH-001', 'price' => 6500, 'category' => 'Mathematics', 'level' => 'advanced'],
        ];

        $courses = [];
        foreach ($coursesData as $courseData) {
            $course = Course::create([
                'name' => $courseData['name'],
                'code' => $courseData['code'],
                'class' => $courseData['class'],
                'price' => $courseData['price'],
                'category' => $courseData['category'],
                'level' => $courseData['level'],
                'description' => "Comprehensive {$courseData['name']} course for Class {$courseData['class']} students.",
                'duration' => rand(3, 6),
                'duration_unit' => 'months',
                'status' => 'active',
                'max_students' => rand(20, 40),
            ]);
            $courses[] = $course;
        }

        // Create batches for each course
        $batchSchedules = ['Mon-Wed-Fri 10:00 AM', 'Tue-Thu-Sat 2:00 PM', 'Sun-Tue-Thu 4:00 PM', 'Mon-Wed 6:00 PM'];
        $batchStatuses = ['active', 'active', 'active', 'inactive'];
        
        foreach ($courses as $index => $course) {
            // Create 1-2 batches per course
            $batchCount = rand(1, 2);
            for ($i = 1; $i <= $batchCount; $i++) {
                $batch = Batch::create([
                    'name' => $course->name . ' - Batch ' . chr(64 + $i),
                    'code' => $course->code . '-B' . $i,
                    'course_id' => $course->id,
                    'schedule' => $batchSchedules[array_rand($batchSchedules)],
                    'start_date' => now()->subMonths(rand(1, 6)),
                    'end_date' => now()->addMonths(rand(3, 6)),
                    'max_students' => rand(20, 35),
                    'status' => $batchStatuses[array_rand($batchStatuses)],
                    'room' => 'Room ' . rand(101, 305),
                ]);

                // Create 5-15 students per batch
                if ($batch->status === 'active') {
                    $studentCount = rand(5, 15);
                    for ($j = 1; $j <= $studentCount; $j++) {
                        // Create user for student
                        $user = User::create([
                            'name' => 'Student ' . $course->class . '-' . $batch->code . '-' . $j,
                            'email' => 'student' . $course->class . '_' . $batch->id . '_' . $j . '@example.com',
                            'password' => bcrypt('password'),
                        ]);

                        // Assign student role
                        $studentRole = \App\Models\Role::where('slug', 'student')->first();
                        if ($studentRole) {
                            $user->roles()->attach($studentRole->id);
                        }

                        // Create student profile
                        Student::create([
                            'user_id' => $user->id,
                            'batch_id' => $batch->id,
                            'class' => $course->class,
                            'registration_no' => date('Y') . '-' . $batch->code . '-' . str_pad($j, 4, '0', STR_PAD_LEFT),
                            'name_bn' => 'শিক্ষার্থী ' . $j,
                            'dob' => now()->subYears(5 + $course->class)->subDays(rand(1, 365)),
                            'gender' => rand(0, 1) ? 'Male' : 'Female',
                            'blood_group' => ['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'][rand(0, 7)],
                            'phone' => '01' . rand(700000000, 799999999),
                            'father_name' => 'Father of Student ' . $j,
                            'mother_name' => 'Mother of Student ' . $j,
                            'father_phone' => '01' . rand(700000000, 799999999),
                            'mother_phone' => '01' . rand(700000000, 799999999),
                            'present_village' => 'Village ' . rand(1, 100),
                            'present_dist' => ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna'][rand(0, 4)],
                            'total_amount' => $course->price,
                            'paid_amount' => $course->price * (rand(50, 100) / 100),
                            'due_amount' => $course->price * (rand(0, 50) / 100),
                            'featured' => rand(0, 10) > 8, // 20% chance of being featured
                        ]);
                    }
                }
            }
        }

        $this->command->info('Created ' . count($courses) . ' courses with batches and students across all classes!');
    }
}

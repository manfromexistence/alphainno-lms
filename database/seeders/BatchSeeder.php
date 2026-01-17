<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        $teachers = Teacher::all();

        $schedules = [
            'Saturday-Sunday: 10:00 AM - 12:00 PM',
            'Monday-Wednesday: 6:00 PM - 8:00 PM',
            'Tuesday-Thursday: 7:00 PM - 9:00 PM',
            'Friday-Saturday: 2:00 PM - 4:00 PM',
            'Sunday-Tuesday: 5:00 PM - 7:00 PM'
        ];

        $rooms = ['Room 101', 'Room 102', 'Room 201', 'Room 202', 'Lab 1', 'Lab 2'];

        foreach ($courses as $course) {
            // Create 2-3 batches per course
            $batchCount = rand(2, 3);

            for ($i = 1; $i <= $batchCount; $i++) {
                $startDate = now()->addDays(rand(7, 60));
                $endDate = $startDate->copy()->addMonths($course->duration);

                Batch::updateOrCreate(
                    ['code' => $course->code . '-B' . $i],
                    [
                        'name' => $course->name . ' - Batch ' . $i,
                        'course_id' => $course->id,
                        'schedule' => $schedules[array_rand($schedules)],
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'max_students' => rand(15, 30),
                        'status' => 'active',
                        'room' => $rooms[array_rand($rooms)],
                        'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->user_id : null
                    ]
                );
            }
        }
    }
}

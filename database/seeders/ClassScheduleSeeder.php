<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\Batch;
use App\Models\Teacher;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = Batch::all();
        $teachers = Teacher::all();

        if ($batches->isEmpty()) {
            $this->command->warn('No batches found. Please seed batches first.');
            return;
        }

        // Days of the week
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        // Subjects for different classes
        $subjects = [
            'Mathematics',
            'English',
            'Science',
            'Physics',
            'Chemistry',
            'Biology',
            'History',
            'Geography',
            'Computer Science',
            'Bangla',
            'Islamic Studies',
            'Social Science',
        ];

        // Time slots
        $timeSlots = [
            ['start' => '09:00:00', 'end' => '10:30:00'],
            ['start' => '10:45:00', 'end' => '12:15:00'],
            ['start' => '12:30:00', 'end' => '14:00:00'],
            ['start' => '14:15:00', 'end' => '15:45:00'],
            ['start' => '16:00:00', 'end' => '17:30:00'],
        ];

        $rooms = ['Room 101', 'Room 102', 'Room 103', 'Room 201', 'Room 202', 'Room 203', 'Lab 1', 'Lab 2', 'Computer Lab'];

        foreach ($batches as $batch) {
            // Create 4-6 classes per week for each batch
            $classesPerWeek = rand(4, 6);
            $usedDays = [];

            for ($i = 0; $i < $classesPerWeek; $i++) {
                // Select a random day that hasn't been used yet
                $availableDays = array_diff($days, $usedDays);
                if (empty($availableDays)) {
                    break;
                }
                
                $day = $availableDays[array_rand($availableDays)];
                $usedDays[] = $day;

                // Select random time slot
                $timeSlot = $timeSlots[array_rand($timeSlots)];
                
                // Select random subject
                $subject = $subjects[array_rand($subjects)];

                // Select random teacher if available
                $teacherId = $teachers->isNotEmpty() ? $teachers->random()->id : null;

                // Select random room
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
            }
        }

        $this->command->info('Class schedules seeded successfully!');
    }
}

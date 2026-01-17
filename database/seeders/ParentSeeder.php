<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        // Get all students
        $students = Student::with('user')->get();
        
        if($students->isEmpty()) {
            echo "No students found. Skipping ParentSeeder.\n";
            return;
        }

        $parentCount = 0;
        $relationshipCount = 0;
        
        // Get the starting number for parent emails/phones
        $existingParentCount = \DB::table('parents')->count();
        $startNumber = $existingParentCount + 1;

        // Create parents for students (some students share parents)
        $createdParents = [];
        
        foreach($students as $index => $student) {
            // 70% chance student has a parent account
            if($faker->boolean(70)) {
                // 30% chance to reuse an existing parent (siblings)
                if($faker->boolean(30) && count($createdParents) > 0) {
                    $parent = $faker->randomElement($createdParents);
                } else {
                    // Create new parent
                    $currentNumber = $startNumber + $parentCount;
                    $parent = ParentModel::create([
                        'name' => $faker->name(),
                        'email' => 'parent' . $currentNumber . '@example.com',
                        'phone' => '017' . str_pad($currentNumber, 8, '0', STR_PAD_LEFT),
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
                $relationshipType = $faker->randomElement(['father', 'mother', 'guardian']);
                
                // Check if relationship already exists
                $exists = \DB::table('parent_student')
                    ->where('parent_id', $parent->id)
                    ->where('student_id', $student->id)
                    ->exists();
                
                if(!$exists) {
                    $parent->students()->attach($student->id, [
                        'relationship_type' => $relationshipType,
                        'status' => 'approved',
                        'approved_by' => 1, // Super admin
                        'approved_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $relationshipCount++;
                }
            }
        }

        echo "Created $parentCount parents!\n";
        echo "Created $relationshipCount parent-student relationships!\n";
    }
}

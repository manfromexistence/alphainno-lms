<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseMaterial;
use App\Models\Course;

class CourseMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        // Get all courses
        $courses = Course::limit(20)->get();
        
        if($courses->isEmpty()) {
            echo "No courses found. Skipping CourseMaterialSeeder.\n";
            return;
        }

        $materialCount = 0;
        $materialTypes = ['pdf', 'video', 'link', 'document'];

        foreach($courses as $course) {
            // Add 3-5 materials per course
            $numMaterials = rand(3, 5);
            
            for($i = 1; $i <= $numMaterials; $i++) {
                $type = $faker->randomElement($materialTypes);
                
                $materialData = [
                    'course_id' => $course->id,
                    'title' => $course->name . ' - Material ' . $i,
                    'description' => $faker->sentence(10),
                    'type' => $type,
                    'order' => $i,
                ];

                // Set file path or external URL based on type
                if($type === 'pdf') {
                    $materialData['file_path'] = 'materials/sample-' . $faker->uuid . '.pdf';
                } elseif($type === 'video') {
                    $materialData['external_url'] = 'https://www.youtube.com/watch?v=' . $faker->regexify('[A-Za-z0-9]{11}');
                } elseif($type === 'link') {
                    $materialData['external_url'] = 'https://example.com/resource/' . $faker->slug;
                } else { // document
                    $materialData['file_path'] = 'materials/document-' . $faker->uuid . '.docx';
                }

                CourseMaterial::create($materialData);
                $materialCount++;
            }
        }

        echo "Created $materialCount course materials!\n";
    }
}

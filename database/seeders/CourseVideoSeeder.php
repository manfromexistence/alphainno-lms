<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseVideoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding course videos...');
        
        // Real Educational YouTube video IDs
        $youtubeIds = [
            'RF_eOpCLayA', // Learn Python - Full Course for Beginners (freeCodeCamp)
            'PkZNo7MFNFg', // Learn JavaScript - Full Course for Beginners (freeCodeCamp)
            'pQN-pnXPaVg', // HTML Full Course - Build a Website Tutorial (freeCodeCamp)
            'yfoY53QXEnI', // CSS Tutorial - Zero to Hero (freeCodeCamp)
            'RGOj5yH7evk', // Git and GitHub for Beginners - Crash Course (freeCodeCamp)
            'mJrhQ-_6pkk', // Docker Tutorial for Beginners (Programming with Mosh)
            'SLpUKAGnm-g', // SQL Tutorial - Full Database Course for Beginners (freeCodeCamp)
            'nu_pCVPKzTk', // React Course - Beginner's Tutorial for React (freeCodeCamp)
            'ENrzD9HAZK4', // Node.js Tutorial for Beginners (Programming with Mosh)
            '_uQrJ0TkZlc', // Python Tutorial - Python Full Course for Beginners (Programming with Mosh)
            'XsxDH4HcOWA', // JavaScript Tutorial for Beginners (Programming with Mosh)
            '8JJ101D3knE', // Git Tutorial for Beginners - Learn Git in 1 Hour (Programming with Mosh)
            'zOjov-2OZ0E', // React Tutorial for Beginners (Programming with Mosh)
            'Wm6CUkswsNw', // Data Structures Easy to Advanced Course (freeCodeCamp)
            'RBSGKlAvoiM', // Data Structures and Algorithms in Python (freeCodeCamp)
            'Xj7k7RYu-r4', // Web Development In 2024 - A Practical Guide (Traversy Media)
            'hdI2bqOjy3c', // JavaScript Crash Course For Beginners (Traversy Media)
            'Oe421EPjeBE', // Next.js Crash Course (Traversy Media)
            'vmEHCJofslg', // Web Design Tutorial (DesignCourse)
            'UB1O30fR-EE', // HTML Crash Course For Absolute Beginners (Traversy Media)
        ];
        
        // Get all existing courses
        $courses = DB::table('courses')->get();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found! Please seed courses first.');
            return;
        }
        
        $videos = [];
        
        foreach ($courses as $course) {
            $videoCount = rand(3, 8);
            
            for ($j = 1; $j <= $videoCount; $j++) {
                $videos[] = [
                    'course_id' => $course->id,
                    'title' => "Lesson {$j}: Introduction to Topic {$j}",
                    'description' => "This is a detailed video explanation for lesson {$j} of {$course->name}.",
                    'video_type' => 'youtube',
                    'external_id' => $youtubeIds[array_rand($youtubeIds)],
                    'duration' => rand(300, 3600),
                    'order' => $j,
                    'is_preview' => $j === 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Clear existing videos
        DB::table('course_videos')->truncate();
        
        // Insert in chunks to avoid query size limits
        foreach (array_chunk($videos, 500) as $chunk) {
            DB::table('course_videos')->insert($chunk);
        }
        
        $this->command->info('Successfully seeded ' . count($videos) . ' videos for ' . $courses->count() . ' courses!');
    }
}

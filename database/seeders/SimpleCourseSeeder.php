<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimpleCourseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding simple courses...');
        
        // Clear existing courses
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        DB::table('courses')->truncate();
        
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $courses = [
            [
                'name' => 'Complete Web Development Bootcamp',
                'code' => 'WEB-101',
                'description' => 'Learn full-stack web development from scratch with HTML, CSS, JavaScript, PHP, and Laravel.',
                'price' => 15000,
                'status' => 'active',
                'level' => 'beginner',
                'duration' => 12,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Science with Python',
                'code' => 'DS-201',
                'description' => 'Master data analysis, machine learning, and statistical modeling with Python.',
                'price' => 20000,
                'status' => 'active',
                'level' => 'intermediate',
                'duration' => 10,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mobile App Development with Flutter',
                'code' => 'MOB-301',
                'description' => 'Build cross-platform mobile applications using Flutter and Dart.',
                'price' => 18000,
                'status' => 'active',
                'level' => 'intermediate',
                'duration' => 8,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Digital Marketing Masterclass',
                'code' => 'MKT-401',
                'description' => 'Complete guide to SEO, social media marketing, and content strategy.',
                'price' => 12000,
                'status' => 'active',
                'level' => 'beginner',
                'duration' => 6,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advanced JavaScript & React',
                'code' => 'JS-501',
                'description' => 'Deep dive into modern JavaScript, ES6+, and React framework.',
                'price' => 16000,
                'status' => 'active',
                'level' => 'advanced',
                'duration' => 10,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Graphic Design Fundamentals',
                'code' => 'GD-101',
                'description' => 'Learn visual design principles, typography, and color theory using Adobe Creative Suite.',
                'price' => 14000,
                'status' => 'active',
                'level' => 'beginner',
                'duration' => 8,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1626785774573-4b7993143a23?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cybersecurity Essentials',
                'code' => 'SEC-201',
                'description' => 'Understand network security, ethical hacking, and how to protect digital assets.',
                'price' => 22000,
                'status' => 'active',
                'level' => 'intermediate',
                'duration' => 12,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cloud Computing with AWS',
                'code' => 'AWS-301',
                'description' => 'Deploy scalable applications and manage infrastructure on Amazon Web Services.',
                'price' => 25000,
                'status' => 'active',
                'level' => 'advanced',
                'duration' => 14,
                'duration_unit' => 'weeks',
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&q=80',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('courses')->insert($courses);
        
        $this->command->info('Successfully seeded ' . count($courses) . ' courses with images!');
    }
}

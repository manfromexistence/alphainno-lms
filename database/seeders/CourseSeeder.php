<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Complete Web Development Bootcamp',
                'code' => 'WEB001',
                'description' => 'Learn full-stack web development from scratch with HTML, CSS, JavaScript, PHP, and Laravel.',
                'price' => 15000.00,
                'duration' => 6,
                'duration_unit' => 'months',
                'status' => 'active',
                'class' => '10',
                'image' => 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?w=400&h=250&fit=crop',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addMonths(6),
                'max_students' => 30,
                'category' => 'Programming',
                'level' => 'beginner',
                'prerequisites' => ['Basic computer knowledge'],
                'objectives' => [
                    'Build responsive websites',
                    'Master frontend technologies',
                    'Learn backend development',
                    'Deploy applications'
                ],
                'syllabus' => [
                    'HTML & CSS Fundamentals',
                    'JavaScript & DOM Manipulation',
                    'PHP & Laravel Framework',
                    'Database Design & MySQL',
                    'Project Development'
                ]
            ],
            [
                'name' => 'Data Science with Python',
                'code' => 'DS001',
                'description' => 'Master data analysis, machine learning, and statistical modeling with Python.',
                'price' => 20000.00,
                'duration' => 8,
                'duration_unit' => 'months',
                'status' => 'active',
                'class' => '11',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=250&fit=crop',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addMonths(8),
                'max_students' => 25,
                'category' => 'Data Science',
                'level' => 'intermediate',
                'prerequisites' => ['Python programming', 'Basic statistics'],
                'objectives' => [
                    'Data analysis and visualization',
                    'Machine learning algorithms',
                    'Statistical modeling',
                    'Big data processing'
                ],
                'syllabus' => [
                    'Python for Data Science',
                    'NumPy & Pandas',
                    'Data Visualization',
                    'Machine Learning',
                    'Deep Learning Basics'
                ]
            ],
            [
                'name' => 'Digital Marketing Mastery',
                'code' => 'DM001',
                'description' => 'Complete guide to digital marketing including SEO, SEM, social media, and analytics.',
                'price' => 12000.00,
                'duration' => 4,
                'duration_unit' => 'months',
                'status' => 'active',
                'class' => '9',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=250&fit=crop',
                'start_date' => now()->addDays(21),
                'end_date' => now()->addMonths(4),
                'max_students' => 40,
                'category' => 'Marketing',
                'level' => 'beginner',
                'prerequisites' => ['Basic internet knowledge'],
                'objectives' => [
                    'SEO and SEM strategies',
                    'Social media marketing',
                    'Content marketing',
                    'Analytics and reporting'
                ],
                'syllabus' => [
                    'Digital Marketing Fundamentals',
                    'Search Engine Optimization',
                    'Social Media Marketing',
                    'Google Ads & Analytics',
                    'Content Strategy'
                ]
            ],
            [
                'name' => 'Mobile App Development with Flutter',
                'code' => 'MOB001',
                'description' => 'Build cross-platform mobile applications using Flutter and Dart.',
                'price' => 18000.00,
                'duration' => 5,
                'duration_unit' => 'months',
                'status' => 'active',
                'class' => '12',
                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=250&fit=crop',
                'start_date' => now()->addDays(28),
                'end_date' => now()->addMonths(5),
                'max_students' => 20,
                'category' => 'Mobile Development',
                'level' => 'intermediate',
                'prerequisites' => ['Programming basics', 'Object-oriented concepts'],
                'objectives' => [
                    'Flutter framework mastery',
                    'Cross-platform development',
                    'UI/UX design principles',
                    'App deployment'
                ],
                'syllabus' => [
                    'Dart Programming',
                    'Flutter Widgets',
                    'State Management',
                    'Firebase Integration',
                    'App Store Deployment'
                ]
            ],
            [
                'name' => 'Graphic Design Fundamentals',
                'code' => 'GD001',
                'description' => 'Learn professional graphic design using Adobe Creative Suite.',
                'price' => 10000.00,
                'duration' => 3,
                'duration_unit' => 'months',
                'status' => 'active',
                'class' => '8',
                'image' => 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=400&h=250&fit=crop',
                'start_date' => now()->addDays(35),
                'end_date' => now()->addMonths(3),
                'max_students' => 25,
                'category' => 'Design',
                'level' => 'beginner',
                'prerequisites' => ['Basic computer skills'],
                'objectives' => [
                    'Adobe Creative Suite proficiency',
                    'Design principles and theory',
                    'Brand identity creation',
                    'Print and digital design'
                ],
                'syllabus' => [
                    'Design Theory & Principles',
                    'Adobe Photoshop',
                    'Adobe Illustrator',
                    'Adobe InDesign',
                    'Portfolio Development'
                ]
            ]
        ];

        // Create standard courses for all classes 1-12 to ensure coverage
        foreach (range(1, 12) as $class) {
            // Check if we already added a course for this class above (just for variety)
            $exists = false;
            foreach($courses as $c) {
                if(isset($c['class']) && $c['class'] == $class) $exists = true;
            }
            
            if(!$exists) {
                $courses[] = [
                    'name' => "Standard Curriculum - Class $class",
                    'code' => "STD-CLS-$class",
                    'description' => "Standard academic curriculum for Class $class students.",
                    'price' => 5000 + ($class * 500),
                    'duration' => 12,
                    'duration_unit' => 'months',
                    'status' => 'active',
                    'class' => (string)$class,
                    'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&h=250&fit=crop', // Generic school image
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                    'max_students' => 50,
                    'category' => 'Academic',
                    'level' => 'beginner',
                    'prerequisites' => [],
                    'objectives' => ["Complete Class $class syllabus"],
                    'syllabus' => ["Subject 1", "Subject 2", "Subject 3"]
                ];
            }
        }

        foreach ($courses as $course) {
            Course::updateOrCreate(['code' => $course['code']], $course);
        }
    }
}

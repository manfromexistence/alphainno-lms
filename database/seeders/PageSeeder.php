<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home Page',
                'slug' => 'home',
                'meta_title' => 'Welcome to Alphainno Lms',
                'meta_description' => 'A leading educational institution providing quality education.',
                'content' => [
                    'slide1_title' => 'স্বাগতম এক্সওয়াইজেডস স্কুল এন্ড',
                    'slide1_subtitle' => 'কলেজে এর পক্ষ থেকে!',
                    'slide1_image' => 'https://plus.unsplash.com/premium_photo-1677567996070-68fa4181775a?q=80&w=1172&auto=format&fit=crop',
                    'slide2_title' => 'শিক্ষার আলোয় আলোকিত',
                    'slide2_subtitle' => 'ভবিষ্যৎ প্রজন্ম',
                    'slide2_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1920',
                    'slide3_title' => 'মানসম্মত শিক্ষা',
                    'slide3_subtitle' => 'আধুনিক শিক্ষা ব্যবস্থা',
                    'slide3_image' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1920',
                    'banner_image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800',
                    'banner_title' => 'Alphainno',
                    'banner_title_highlight' => 'এর সাথেই শিখাছে',
                    'banner_subtitle' => 'সবাই, জিতছে সবাই',
                    'banner_description' => 'এখন তুমি যেখানেই থাকো, ম্যাচার কথা না কিছু! কারণ সবাই শিখাছে আর জিতছে দেশের সবচেয়ে বিশ্বস্ত ডিজিটাল লার্নিং প্ল্যাটফর্ম শিখোতে।',
                    'banner_button' => 'আমাদের সম্পর্কে জেনে নাও',
                    'courses_section_title' => 'জনপ্রিয় কোর্সসমূহ',
                    'courses_section_subtitle' => 'আমাদের সবচেয়ে জনপ্রিয় এবং চাহিদা সম্পন্ন কোর্সগুলি দেখুন',
                    'students_section_title' => 'আমাদের সেরা শিক্ষার্থীরা',
                    'students_section_subtitle' => 'যারা এক্সেলেন্স এবং ডেডিকেশনের সাথে তাদের শিক্ষাজীবন অতিবাহিত করছেন',
                    'about_section_title' => 'প্রতিষ্ঠান সম্পর্কে',
                    'about_section_text1' => 'এক্সওয়াইজেডস স্কুল এন্ড কলেজ বাংলাদেশের একটি অগ্রগামী শিক্ষা প্রতিষ্ঠান যা ১৯৮৮ সালে প্রতিষ্ঠিত হয়। আমরা শিক্ষার্থীদের মেধা ও মনন বিকাশে প্রতিশ্রুতিবদ্ধ।',
                    'about_section_text2' => 'আমাদের লক্ষ্য মানসম্মত শিক্ষা প্রদান করা এবং প্রতিটি শিক্ষার্থীকে সুনাগরিক হিসেবে গড়ে তোলা। আমাদের রয়েছে অভিজ্ঞ শিক্ষকমণ্ডলী এবং আধুনিক ল্যাব সুবিধা।',
                    'about_section_button' => 'বিস্তারিত পড়ুন',
                    'about_section_image' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=600',
                    'notice_title' => 'নোটিশ বোর্ড',
                    'notice_1' => 'নতুন শিক্ষাবর্ষের ভর্তি কার্যক্রম শুরু হয়েছে। বিস্তারিত জানতে যোগাযোগ করুন।',
                    'notice_view_all' => 'সকল নোটিশ',
                ],
            ],
            [
                'title' => 'About Page',
                'slug' => 'about',
                'meta_title' => 'About Us - Alphainno Lms',
                'meta_description' => 'Learn about our history, mission, and vision.',
                'content' => [
                    'page_title' => 'আমাদের সম্পর্কে',
                    'about_image' => 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8dW5pdmVyc2l0eXxlbnwwfHwwfHx8MA%3D%3D',
                    'about_title' => 'আমাদের ইতিহাস ও ঐতিহ্য',
                    'about_text' => 'Alphainno Lms ১৯৮৮ সালে প্রতিষ্ঠিত একটি স্বনামধন্য শিক্ষা প্রতিষ্ঠান। বিগত তিন দশক ধরে আমরা নিরলসভাবে শিক্ষা সেবা প্রদান করে আসছি। আমাদের প্রতিষ্ঠানটি ঢাকা বোর্ডের অন্যতম সেরা শিক্ষা প্রতিষ্ঠান হিসেবে স্বীকৃত।',
                    'stats_students' => '২৫০০+',
                    'stats_teachers' => '১২০+',
                    'stats_staff' => '৪৫+',
                    'stats_rooms' => '৬০+',
                    'stats_buildings' => '৩টি',
                    'mission_title' => 'আমাদের লক্ষ্য (Mission)',
                    'mission_text' => 'আমাদের লক্ষ্য হলো শিক্ষার্থীদের আধুনিক ও নৈতিক শিক্ষায় শিক্ষিত করে তোলা। আমরা বিশ্বাস করি প্রতিটি শিশুই সম্ভাবনাময়। তাদের সুপ্ত প্রতিভা বিকাশে আমরা বদ্ধপরিকর।',
                    'vision_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800',
                    'vision_title' => 'আমাদের ভিশন (Vision)',
                    'vision_text' => 'ডিজিটাল বাংলাদেশ গড়ার লক্ষ্যে আমরা প্রযুক্তি নির্ভর শিক্ষা ব্যবস্থা প্রবর্তন করেছি। আমাদের ভিশন হলো এমন একটি প্রজন্ম তৈরি করা যারা একবিংশ শতাব্দীর চ্যালেঞ্জ মোকাবেলায় সক্ষম হবে।',
                ],
            ],
            [
                'title' => 'Contact Page',
                'slug' => 'contact',
                'meta_title' => 'Contact Us - Alphainno Lms',
                'meta_description' => 'Get in touch with us.',
                'content' => [
                    'page_title' => 'যোগাযোগ করুন',
                    'page_subtitle' => 'যেকোনো প্রয়োজনে আমাদের সাথে যোগাযোগ করুন',
                    'form_title' => 'বার্তা পাঠান',
                    'address' => 'House # 12, Road # 5, Dhanmondi, Dhaka-1209, Bangladesh',
                    'phone' => '+880 1712 345678',
                    'email' => 'info@xyzschool.edu.bd',
                    'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.9024424301323!2d90.39108011538397!3d23.75085809467657!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b888ad3df4ab%3A0x303ab119fa9d1502!2sDhanmondi%2C%20Dhaka%201205!5e0!3m2!1sen!2sbd!4v1646830573985!5m2!1sen!2sbd',
                ],
            ],
            [
                'title' => 'Courses Page',
                'slug' => 'courses',
                'meta_title' => 'Our Courses',
                'meta_description' => 'Explore our available courses.',
                'content' => [
                    'page_title' => 'কোর্সসমূহ',
                    'page_subtitle' => 'আমাদের সকল একাডেমিক ও স্কিল ডেভেলপমেন্ট কোর্স',
                ],
            ],
            [
                'title' => 'Teachers Page',
                'slug' => 'teachers',
                'meta_title' => 'Our Teachers',
                'meta_description' => 'Meet our experienced faculty members.',
                'content' => [
                    'page_title' => 'শিক্ষকমণ্ডলী',
                    'page_subtitle' => 'আমাদের অভিজ্ঞ ও দক্ষ শিক্ষকমণ্ডলী',
                ],
            ],
            [
                'title' => 'Students Page',
                'slug' => 'students',
                'meta_title' => 'Our Students',
                'meta_description' => 'Student activities and achievements.',
                'content' => [
                    'page_title' => 'আমাদের শিক্ষার্থী',
                    'page_subtitle' => 'শিক্ষার্থীদের সকল তথ্য ও কার্যক্রম',
                    'stats_title' => 'শিক্ষার্থী পরিসংখ্যান',
                    'total_students' => '2500+',
                    'male_students' => '1200+',
                    'female_students' => '1300+',
                    'attendance_rate' => '95%',
                    'activities_title' => 'সহশিক্ষা কার্যক্রম',
                    'activity1_title' => 'ডিবেটিং ক্লাব',
                    'activity1_text' => 'শিক্ষার্থীদের যুক্তি ও মেধা বিকাশের জন্য রয়েছে সক্রিয় ডিবেটিং ক্লাব।',
                    'activity2_title' => 'স্পোর্টস ক্লাব',
                    'activity2_text' => 'বার্ষিক ক্রীড়া প্রতিযোগিতা ও নিয়মিত খেলাধুলার ব্যবস্থা।',
                    'activity3_title' => 'সাংস্কৃতিক সংঘ',
                    'activity3_text' => 'নৃত্য, সংগীত ও আবৃত্তি চর্চার জন্য রয়েছে সাংস্কৃতিক সংঘ।',
                ],
            ],
            [
                'title' => 'Results Page',
                'slug' => 'results',
                'meta_title' => 'Exam Results',
                'meta_description' => 'Check exam results online.',
                'content' => [
                    'page_title' => 'ফলাফল',
                    'page_subtitle' => 'একাডেমিক পরীক্ষার ফলাফল দেখুন',
                    'search_title' => 'ফলাফল খুঁজুন',
                    'exam_type_label' => 'পরীক্ষার ধরন',
                    'roll_label' => 'রোল নম্বর',
                    'roll_placeholder' => 'রোল নম্বর ইংরেজিতে লিখুন (যেমন: 1001)',
                    'reg_label' => 'রেজিস্ট্রেশন নম্বর (অপশনাল)',
                    'search_button' => 'ফলাফল দেখুন',
                    'achievements_title' => 'আমাদের সাফল্য',
                    'avg_pass_rate' => '৯৮%',
                    'avg_pass_rate_label' => 'গড় পাসের হার',
                    'gpa5_count' => '৫০০+',
                    'gpa5_label' => 'মোট জিপিএ-৫',
                    'aplus_count' => '৮০০+',
                    'aplus_label' => 'মোট এ+',
                    'scholarship_rate' => '১৫%',
                    'scholarship_label' => 'বৃত্তি প্রাপ্ত',
                ],
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'meta_title' => $page['meta_title'],
                    'meta_description' => $page['meta_description'],
                    'content' => $page['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}

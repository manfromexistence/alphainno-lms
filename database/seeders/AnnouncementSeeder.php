<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have an admin user for 'created_by'
        $admin = User::role('super-admin')->first() ?? User::first();

        $announcements = [
            [
                'title' => 'নতুন শিক্ষাবর্ষের ভর্তি কার্যক্রম শুরু',
                'content' => '২০২৬ শিক্ষাবর্ষের জন্য সকল শ্রেণিতে ভর্তি কার্যক্রম শুরু হয়েছে। আগ্রহী অভিভাবকদের অফিস চলাকালীন সময়ে যোগাযোগ করার জন্য অনুরোধ করা হলো।',
                'type' => 'notice',
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'এসএসসি পরীক্ষার্থীদের বিদায় অনুষ্ঠান',
                'content' => 'আগামী ২০শে জানুয়ারি এসএসসি পরীক্ষার্থীদের বিদায় সংবর্ধনা ও দোয়া মাহফিল অনুষ্ঠিত হবে। সকল শিক্ষার্থীকে উপস্থিত থাকার জন্য বলা হলো।',
                'type' => 'event',
                'priority' => 'medium',
                'status' => 'published',
            ],
            [
                'title' => 'বার্ষিক ক্রীড়া প্রতিযোগিতা',
                'content' => 'আগামী ১০ই ফেব্রুয়ারি বার্ষিক ক্রীড়া প্রতিযোগিতা অনুষ্ঠিত হবে। অংশগ্রহণে ইচ্ছুক শিক্ষার্থীদের ক্রীড়া শিক্ষকের সাথে যোগাযোগ করতে হবে।',
                'type' => 'event',
                'priority' => 'medium',
                'status' => 'published',
            ],
            [
                'title' => 'শীতকালীন ছুটির বিজ্ঞপ্তি',
                'content' => 'বিদ্যালয়ের শীতকালীন ছুটি আগামী ২৫শে ডিসেম্বর থেকে ৩১শে ডিসেম্বর পর্যন্ত বলবৎ থাকবে। ১লা জানুয়ারি যথারীতি ক্লাস শুরু হবে।',
                'type' => 'notice',
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'অভিভাবক সমাবেশ',
                'content' => 'আগামী শুক্রবার সকাল ১০টায় স্কুল অডিটোরিয়ামে অভিভাবক সমাবেশ অনুষ্ঠিত হবে। সকল অভিভাবকের উপস্থিতি কাম্য।',
                'type' => 'meeting',
                'priority' => 'high',
                'status' => 'published',
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create(array_merge($announcement, [
                'created_by' => $admin?->id,
                'published_at' => now(),
            ]));
        }
    }
}

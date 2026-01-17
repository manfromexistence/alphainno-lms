<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\SmsLog;
use App\Models\MessageTemplate;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class AdminFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        // 1. Announcements
        $targetTypes = ['all', 'batch', 'course'];
        $priorities = ['normal', 'high', 'urgent'];
        
        foreach(range(1, 15) as $i) {
            Announcement::create([
                'title' => $faker->sentence(4),
                'content' => $faker->paragraph(3),
                'target_type' => $faker->randomElement($targetTypes),
                'target_id' => null,
                'priority' => $faker->randomElement($priorities),
                'starts_at' => $faker->dateTimeBetween('-2 months', 'now'),
                'expires_at' => $faker->dateTimeBetween('now', '+2 months'),
                'is_active' => $faker->boolean(80),
                'created_by' => 1,
            ]);
        }
        
        // 2. SMS Logs
        $students = Student::with('user')->limit(50)->get();
        $smsTypes = ['payment_reminder', 'exam_notification', 'attendance_alert', 'general', 'result_notification'];
        $statuses = ['sent', 'failed', 'pending'];
        
        foreach(range(1, 100) as $i) {
            $student = $students->random();
            $status = $faker->randomElement($statuses);
            
            SmsLog::create([
                'phone' => $student->phone ?? $faker->phoneNumber,
                'message' => $faker->sentence(10),
                'type' => $faker->randomElement($smsTypes),
                'status' => $status,
                'sent_at' => $status === 'sent' ? $faker->dateTimeBetween('-1 month', 'now') : null,
                'related_type' => 'App\Models\Student',
                'related_id' => $student->id,
            ]);
        }
        
        // 3. Message Templates
        $templates = [
            [
                'name' => 'Payment Reminder',
                'slug' => 'payment-reminder',
                'type' => 'payment',
                'content' => 'Dear {student_name}, your payment of {amount} is due on {due_date}. Please pay at your earliest convenience.',
                'placeholders' => ['student_name', 'amount', 'due_date'],
                'is_active' => true,
            ],
            [
                'name' => 'Exam Notification',
                'slug' => 'exam-notification',
                'type' => 'exam',
                'content' => 'Dear {student_name}, your {exam_name} is scheduled on {exam_date} at {exam_time}. Best of luck!',
                'placeholders' => ['student_name', 'exam_name', 'exam_date', 'exam_time'],
                'is_active' => true,
            ],
            [
                'name' => 'Attendance Alert',
                'slug' => 'attendance-alert',
                'type' => 'attendance',
                'content' => 'Dear Parent, your child {student_name} was absent on {date}. Please contact the school if this is unexpected.',
                'placeholders' => ['student_name', 'date'],
                'is_active' => true,
            ],
            [
                'name' => 'Result Published',
                'slug' => 'result-published',
                'type' => 'result',
                'content' => 'Dear {student_name}, your {exam_name} result is now available. You scored {marks}/{total_marks}.',
                'placeholders' => ['student_name', 'exam_name', 'marks', 'total_marks'],
                'is_active' => true,
            ],
            [
                'name' => 'Welcome Message',
                'slug' => 'welcome-message',
                'type' => 'general',
                'content' => 'Welcome to {institute_name}, {student_name}! Your student ID is {student_id}. We wish you success in your studies.',
                'placeholders' => ['institute_name', 'student_name', 'student_id'],
                'is_active' => true,
            ],
        ];
        
        foreach($templates as $template) {
            MessageTemplate::create($template);
        }
        
        // 4. Activity Logs
        $users = User::limit(10)->get();
        $actions = ['created', 'updated', 'deleted', 'viewed', 'exported'];
        $models = ['Student', 'Teacher', 'Course', 'Batch', 'Exam', 'Payment'];
        
        foreach(range(1, 50) as $i) {
            $user = $users->random();
            $model = $faker->randomElement($models);
            $action = $faker->randomElement($actions);
            
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'model_type' => 'App\Models\\' . $model,
                'model_id' => rand(1, 100),
                'description' => ucfirst($action) . ' ' . $model . ' record',
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
        }
        
        echo "Created announcements, SMS logs, message templates, and activity logs!\n";
    }
}

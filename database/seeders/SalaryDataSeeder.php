<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use Carbon\Carbon;

class SalaryDataSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::with('user')->take(10)->get();
        
        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please seed teachers first.');
            return;
        }

        // Clear existing salaries
        TeacherSalary::truncate();

        $months = [
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonths(1),
            Carbon::now(),
        ];

        $paymentMethods = ['Cash', 'Bank Transfer', 'Mobile Banking', 'Cheque'];

        foreach ($teachers as $teacher) {
            foreach ($months as $month) {
                TeacherSalary::create([
                    'teacher_id' => $teacher->id,
                    'amount' => rand(35000, 55000),
                    'month' => $month->format('Y-m'),
                    'payment_date' => $month->copy()->addDays(rand(1, 5)),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'status' => 'paid',
                    'notes' => 'Monthly salary payment for ' . $month->format('F Y'),
                ]);
            }
        }

        $this->command->info('Salary data seeded successfully!');
    }
}

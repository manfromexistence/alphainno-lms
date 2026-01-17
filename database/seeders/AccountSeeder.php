<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Student;
use App\Models\Payment;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Get students and payments for realistic income data
        $students = Student::all();
        $payments = Payment::all();

        // 1. Income - Create diverse income entries
        $incomeCategories = ['admission', 'tuition', 'exam_fee', 'library', 'sports', 'materials', 'other'];
        
        // Create 50 income entries over the past 6 months
        foreach(range(1, 50) as $i) {
            $category = $faker->randomElement($incomeCategories);
            $student = $students->random();
            $payment = $payments->isNotEmpty() ? $payments->random() : null;
            
            $amount = match($category) {
                'admission' => rand(5000, 15000),
                'tuition' => rand(3000, 10000),
                'exam_fee' => rand(500, 2000),
                'library' => rand(200, 1000),
                'sports' => rand(500, 2000),
                'materials' => rand(300, 1500),
                default => rand(500, 5000),
            };
            
            Income::create([
                'amount' => $amount,
                'income_date' => $faker->dateTimeBetween('-6 months', 'now'),
                'category' => $category,
                'description' => $this->getIncomeDescription($category),
                'student_id' => $student->id,
                'payment_id' => $payment?->id,
                'reference' => 'INC-' . strtoupper($faker->bothify('??###')),
                'created_by' => 1,
            ]);
        }

        // 2. Expenses - Create diverse expense entries
        $expenseCategories = [
            'salary' => ['Teacher Salary', 'Staff Salary', 'Admin Salary'],
            'utilities' => ['Electricity Bill', 'Water Bill', 'Internet Bill', 'Phone Bill'],
            'maintenance' => ['Building Repair', 'Equipment Maintenance', 'Cleaning Service'],
            'rent' => ['Office Rent', 'Building Rent'],
            'supplies' => ['Office Supplies', 'Cleaning Supplies', 'Teaching Materials'],
            'transport' => ['Vehicle Fuel', 'Vehicle Maintenance', 'Transport Allowance'],
            'marketing' => ['Advertisement', 'Promotional Materials', 'Website Maintenance'],
            'other' => ['Miscellaneous', 'Emergency Expense', 'Event Cost'],
        ];
        
        // Create 60 expense entries over the past 6 months
        foreach(range(1, 60) as $i) {
            $category = $faker->randomElement(array_keys($expenseCategories));
            $description = $faker->randomElement($expenseCategories[$category]);
            
            $amount = match($category) {
                'salary' => rand(15000, 50000),
                'utilities' => rand(2000, 10000),
                'maintenance' => rand(3000, 20000),
                'rent' => rand(20000, 50000),
                'supplies' => rand(1000, 8000),
                'transport' => rand(1000, 5000),
                'marketing' => rand(2000, 15000),
                default => rand(500, 10000),
            };
            
            Expense::create([
                'amount' => $amount,
                'expense_date' => $faker->dateTimeBetween('-6 months', 'now'),
                'category' => $category,
                'description' => $description,
                'reference' => 'EXP-' . strtoupper($faker->bothify('??###')),
                'created_by' => 1,
            ]);
        }
        
        echo "Created 50 income entries and 60 expense entries!\n";
    }
    
    /**
     * Get description for income category
     */
    private function getIncomeDescription($category): string
    {
        return match($category) {
            'admission' => 'Student Admission Fee',
            'tuition' => 'Monthly Tuition Fee',
            'exam_fee' => 'Examination Fee',
            'library' => 'Library Fee',
            'sports' => 'Sports Fee',
            'materials' => 'Course Materials Fee',
            default => 'Other Income',
        };
    }
}

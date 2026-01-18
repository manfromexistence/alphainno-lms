<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Batch;
use App\Services\DashboardService;
use App\Services\AccountService;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardService $dashboardService;
    protected AccountService $accountService;
    protected ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardService = app(DashboardService::class);
        $this->accountService = app(AccountService::class);
        $this->reportService = app(ReportService::class);
    }

    /** @test */
    public function dashboard_statistics_work_correctly()
    {
        // Create test data
        Student::factory()->count(5)->create();
        Teacher::factory()->count(3)->create();

        $stats = $this->dashboardService->getStatisticsForRole('super-admin');

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_students', $stats);
        $this->assertArrayHasKey('total_teachers', $stats);
        $this->assertEquals(5, $stats['total_students']);
        $this->assertEquals(3, $stats['total_teachers']);
    }

    /** @test */
    public function revenue_chart_data_works_correctly()
    {
        // Create payments in different months
        Payment::factory()->create([
            'amount' => 1000,
            'status' => 'completed',
            'created_at' => now()->subMonths(2),
        ]);

        Payment::factory()->create([
            'amount' => 2000,
            'status' => 'completed',
            'created_at' => now()->subMonth(),
        ]);

        $chartData = $this->dashboardService->getChartData('revenue');

        $this->assertIsArray($chartData);
        $this->assertNotEmpty($chartData);
    }

    /** @test */
    public function attendance_chart_data_works_correctly()
    {
        $student = Student::factory()->create();
        $batch = Batch::factory()->create();

        // Create attendance records
        Attendance::factory()->create([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'date' => now()->toDateString(),
            'status' => 'present',
        ]);

        $chartData = $this->dashboardService->getChartData('attendance');

        $this->assertIsArray($chartData);
        $this->assertNotEmpty($chartData);
    }

    /** @test */
    public function monthly_summary_works_correctly()
    {
        $year = now()->year;
        $month = now()->month;

        $summary = $this->accountService->getMonthlySummary($year, $month);

        $this->assertIsArray($summary);
        $this->assertArrayHasKey('year', $summary);
        $this->assertArrayHasKey('month', $summary);
        $this->assertArrayHasKey('total_income', $summary);
        $this->assertArrayHasKey('total_expense', $summary);
        $this->assertEquals($year, $summary['year']);
        $this->assertEquals($month, $summary['month']);
    }

    /** @test */
    public function payment_report_works_correctly()
    {
        $student = Student::factory()->create();
        
        Payment::factory()->create([
            'student_id' => $student->id,
            'amount' => 5000,
            'status' => 'completed',
            'payment_date' => now(),
        ]);

        $report = $this->reportService->generatePaymentReport([
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $this->assertIsArray($report);
        $this->assertArrayHasKey('data', $report);
        $this->assertArrayHasKey('summary', $report);
    }

    /** @test */
    public function attendance_report_works_correctly()
    {
        $student = Student::factory()->create();
        $batch = Batch::factory()->create();

        Attendance::factory()->create([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'date' => now()->toDateString(),
            'status' => 'present',
        ]);

        $report = $this->reportService->generateAttendanceReport([
            'start_date' => now()->subWeek()->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $this->assertIsArray($report);
        $this->assertArrayHasKey('data', $report);
        $this->assertArrayHasKey('summary', $report);
    }

    /** @test */
    public function enrollment_chart_data_works_correctly()
    {
        // Create students in different months
        Student::factory()->create(['created_at' => now()->subMonths(2)]);
        Student::factory()->create(['created_at' => now()->subMonth()]);
        Student::factory()->create(['created_at' => now()]);

        $chartData = $this->reportService->getChartData('enrollment');

        $this->assertIsArray($chartData);
        $this->assertArrayHasKey('labels', $chartData);
        $this->assertArrayHasKey('data', $chartData);
    }

    /** @test */
    public function date_filtering_works_with_whereBetween()
    {
        // Create students in different months
        $lastMonth = Student::factory()->create([
            'created_at' => now()->subMonth(),
        ]);
        
        $thisMonth = Student::factory()->create([
            'created_at' => now(),
        ]);

        // Test whereBetween filtering
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $students = Student::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

        $this->assertCount(1, $students);
        $this->assertEquals($thisMonth->id, $students->first()->id);
    }

    /** @test */
    public function enrolled_in_year_scope_works_correctly()
    {
        $lastYear = Student::factory()->create([
            'created_at' => now()->subYear(),
        ]);
        
        $thisYear = Student::factory()->create([
            'created_at' => now(),
        ]);

        $students = Student::enrolledInYear(now()->year)->get();

        $this->assertCount(1, $students);
        $this->assertEquals($thisYear->id, $students->first()->id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamResult;
use App\Models\CqSubmission;
use App\Models\Payment;
use App\Models\CourseMaterial;
use App\Services\StudentPortalService;
use App\Services\ExamTakingService;
use App\Services\MarkSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentPortalController extends Controller
{
    public function __construct(
        protected StudentPortalService $portalService,
        protected ExamTakingService $examService,
        protected MarkSheetService $markSheetService
    ) {}

    /**
     * Get the authenticated student.
     */
    private function getStudent(): ?Student
    {
        $user = Auth::user();
        return Student::where('user_id', $user->id)->first();
    }

    /**
     * Student dashboard.
     */
    public function dashboard()
    {
        $student = $this->getStudent();
        
        if (!$student) {
            // Allow Super Admin to view placeholder
            if (Auth::user()->isSuperAdmin()) {
                return view('student.dashboard-placeholder');
            }
            return redirect()->route('dashboard')->with('error', 'Student profile not found.');
        }

        $data = $this->portalService->getDashboardData($student);
        
        return view('student.dashboard', $data);
    }

    /**
     * Course materials.
     */
    public function materials()
    {
        $student = $this->getStudent();
        
        if (!$student) {
            // For admin users without student profile, show all materials
            if (Auth::user()->isAdmin()) {
                $materials = CourseMaterial::with('course')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('type');
                
                return view('student.materials', [
                    'student' => null,
                    'materials' => $materials,
                ]);
            }
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $materials = $this->portalService->getMaterials($student);
        
        return view('student.materials', [
            'student' => $student,
            'materials' => $materials,
        ]);
    }

    /**
     * Download a course material.
     */
    public function downloadMaterial(CourseMaterial $material)
    {
        $student = $this->getStudent();
        
        if (!$student || $student->batch?->course_id !== $material->course_id) {
            abort(403, 'Unauthorized access to this material.');
        }

        if ($material->type === 'link') {
            return redirect()->away($material->file_path);
        }

        if (Storage::disk('public')->exists($material->file_path)) {
            return Storage::disk('public')->download($material->file_path, $material->title);
        }

        return back()->with('error', 'File not found.');
    }

    /**
     * Class schedule.
     */
    public function schedule()
    {
        $student = $this->getStudent();
        
        if (!$student) {
            // For admin users without student profile, show all schedules
            if (Auth::user()->isAdmin()) {
                $schedules = \App\Models\ClassSchedule::with(['batch', 'teacher'])
                    ->orderBy('day_of_week')
                    ->orderBy('start_time')
                    ->get();
                
                return view('student.schedule', [
                    'student' => null,
                    'schedules' => $schedules,
                ]);
            }
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $schedules = $this->portalService->getSchedule($student);
        
        return view('student.schedule', [
            'student' => $student,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Payment history.
     */
    public function payments()
    {
        $student = $this->getStudent();
        
        if (!$student) {
            // For admin users without student profile, show all payments
            if (Auth::user()->isAdmin()) {
                $payments = Payment::with('student')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
                
                $totalFee = Payment::sum('amount');
                $paidAmount = Payment::where('status', 'completed')->sum('amount');
                $dueAmount = $totalFee - $paidAmount;
                
                $summary = [
                    'total_fee' => $totalFee,
                    'paid_amount' => $paidAmount,
                    'due_amount' => $dueAmount,
                    'payment_percentage' => $totalFee > 0 ? round(($paidAmount / $totalFee) * 100, 2) : 0,
                ];
                
                return view('student.payments', [
                    'student' => null,
                    'payments' => $payments,
                    'summary' => $summary,
                ]);
            }
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $payments = $this->portalService->getPaymentHistory($student);
        $summary = $this->portalService->getPaymentSummary($student);
        
        return view('student.payments', [
            'student' => $student,
            'payments' => $payments,
            'summary' => $summary,
        ]);
    }

    /**
     * Download payment receipt.
     */
    public function downloadReceipt(Payment $payment)
    {
        $student = $this->getStudent();
        
        if (!$student || $payment->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        $payment->load(['student.batch.course']);

        $pdf = PDF::loadView('pdf.receipt', [
            'payment' => $payment,
            'student' => $student,
        ]);

        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }

    /**
     * List exams.
     */
    public function exams()
    {
        $student = $this->getStudent();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $upcomingExams = $this->portalService->getUpcomingExams($student);
        
        $pastExams = ExamResult::where('student_id', $student->id)
            ->with('exam')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.exams', [
            'student' => $student,
            'upcomingExams' => $upcomingExams,
            'pastExams' => $pastExams,
        ]);
    }

    /**
     * Start an MCQ exam.
     */
    public function startExam(Exam $exam)
    {
        $student = $this->getStudent();
        
        if (!$student || $exam->batch_id !== $student->batch_id) {
            abort(403, 'Unauthorized access to this exam.');
        }

        $attempt = $this->examService->startAttempt($student, $exam);
        $examData = $this->examService->getExamWithTimer($attempt);

        return view('student.exam-take', $examData);
    }

    /**
     * Take an MCQ exam.
     * 
     * Requirements: 2.1, 5.1, 5.2
     */
    public function takeMCQ(Exam $exam)
    {
        $student = $this->getStudent();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Verify student has access to this exam (same batch)
        if ($exam->batch_id !== $student->batch_id) {
            abort(403, 'Unauthorized access to this exam.');
        }

        // Validate exam time window using ExamTimeValidator
        $timeValidator = app(\App\Services\ExamTimeValidator::class);
        
        if (!$timeValidator->canStartExam($exam)) {
            $timeStatus = $timeValidator->getTimeStatus($exam);
            $message = $timeValidator->getTimeStatusMessage($exam);
            
            return redirect()->route('student.exams')
                ->with('error', $message);
        }

        // Create or retrieve ExamAttempt for student
        $attempt = ExamAttempt::firstOrCreate(
            [
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'status' => 'in_progress',
            ],
            [
                'started_at' => now(),
                'answers' => [],
                'cheating_events' => [],
                'ip_address' => request()->ip(),
            ]
        );

        // If attempt was just created, set started_at
        if (!$attempt->wasRecentlyCreated && !$attempt->started_at) {
            $attempt->update(['started_at' => now()]);
        }

        // Load exam questions with options
        $questions = $exam->questions()
            ->where('type', 'mcq')
            ->orderBy('order')
            ->get();

        // Get saved answers from the attempt
        $savedAnswers = $attempt->answers ?? [];

        // Calculate remaining time
        $remainingTime = $timeValidator->getRemainingTime($attempt);

        // Pass data to view
        return view('student.mcq-exam', [
            'student' => $student,
            'exam' => $exam,
            'attempt' => $attempt,
            'questions' => $questions,
            'timeRemaining' => $remainingTime,
            'savedAnswers' => $savedAnswers,
        ]);
    }

    /**
     * Save answer via AJAX.
     */
    public function saveAnswer(Request $request, ExamAttempt $attempt)
    {
        $student = $this->getStudent();
        
        if (!$student || $attempt->student_id !== $student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'question_id' => 'required|integer',
            'answer' => 'required|string',
        ]);

        $success = $this->examService->saveAnswer(
            $attempt,
            $request->question_id,
            $request->answer
        );

        return response()->json([
            'success' => $success,
            'remaining_time' => $attempt->fresh()->remaining_time,
        ]);
    }

    /**
     * Record tab switch event (anti-cheating).
     * 
     * Requirements: 6.3, 6.4
     */
    public function recordTabSwitch(Request $request, ExamAttempt $attempt)
    {
        $student = $this->getStudent();
        
        if (!$student || $attempt->student_id !== $student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'event_type' => 'required|string|in:tab_switch,fullscreen_exit',
            'timestamp' => 'required|string',
        ]);

        // Get existing cheating events or initialize empty array
        $cheatingEvents = $attempt->cheating_events ?? [];
        
        // Add new event
        $cheatingEvents[] = [
            'type' => $request->event_type,
            'timestamp' => $request->timestamp,
            'recorded_at' => now()->toISOString(),
        ];

        // Update attempt with new events
        $attempt->update([
            'cheating_events' => $cheatingEvents,
        ]);

        return response()->json([
            'success' => true,
            'event_count' => count($cheatingEvents),
        ]);
    }

    /**
     * Submit an exam.
     * 
     * Requirements: 2.2
     * 
     * Task details:
     * - Validate exam attempt ownership
     * - Save all answers to exam_attempts table (already saved via auto-save)
     * - Create ExamResult record with score calculation
     * - Mark attempt as submitted
     * - Redirect to results page
     */
    public function submitExam(Request $request, Exam $exam)
    {
        $student = $this->getStudent();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Validate exam attempt ownership
        $attempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            return redirect()->route('student.exams')->with('error', 'No active attempt found for this exam.');
        }

        // Submit exam (saves answers, creates result, marks as submitted)
        $result = $this->examService->submitExam($attempt);

        // Redirect to results page
        return redirect()->route('student.exam-result', $result->id)
            ->with('success', 'Exam submitted successfully!');
    }

    /**
     * Show exam result with explanations.
     */
    public function examResult(ExamResult $result)
    {
        $student = $this->getStudent();
        
        if (!$student || $result->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this result.');
        }

        $result->load(['exam.questions']);
        
        $attempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $result->exam_id)
            ->first();

        return view('student.exam-result', [
            'student' => $student,
            'result' => $result,
            'attempt' => $attempt,
            'questions' => $result->exam->questions,
        ]);
    }

    /**
     * Take a CQ exam.
     * 
     * Requirements: 5.1, 5.2
     * 
     * Task details:
     * - Validate exam time window
     * - Create or retrieve ExamAttempt
     * - Load CQ questions
     * - Pass data to view
     */
    public function takeCQ(Exam $exam)
    {
        $student = $this->getStudent();
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        // Verify student has access to this exam (same batch)
        if ($exam->batch_id !== $student->batch_id) {
            abort(403, 'Unauthorized access to this exam.');
        }

        // Validate exam time window using ExamTimeValidator
        $timeValidator = app(\App\Services\ExamTimeValidator::class);
        
        if (!$timeValidator->canStartExam($exam)) {
            $timeStatus = $timeValidator->getTimeStatus($exam);
            $message = $timeValidator->getTimeStatusMessage($exam);
            
            return redirect()->route('student.exams')
                ->with('error', $message);
        }

        // Create or retrieve ExamAttempt for student
        $attempt = ExamAttempt::firstOrCreate(
            [
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'status' => 'in_progress',
            ],
            [
                'started_at' => now(),
                'answers' => [],
                'cheating_events' => [],
                'screenshots' => [],
                'ip_address' => request()->ip(),
            ]
        );

        // If attempt was just created, set started_at
        if (!$attempt->wasRecentlyCreated && !$attempt->started_at) {
            $attempt->update(['started_at' => now()]);
        }

        // Load CQ questions
        $questions = $exam->questions()
            ->where('type', 'cq')
            ->orderBy('order')
            ->get();

        // Calculate remaining time
        $remainingTime = $timeValidator->getRemainingTime($attempt);

        // Pass data to view
        return view('student.cq-exam', [
            'student' => $student,
            'exam' => $exam,
            'attempt' => $attempt,
            'questions' => $questions,
            'timeRemaining' => $remainingTime,
        ]);
    }

    /**
     * Show CQ exam question paper.
     */
    public function showCqExam(Exam $exam)
    {
        $student = $this->getStudent();
        
        if (!$student || $exam->batch_id !== $student->batch_id) {
            abort(403, 'Unauthorized access to this exam.');
        }

        $submission = CqSubmission::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->first();

        return view('student.cq-exam', [
            'student' => $student,
            'exam' => $exam,
            'submission' => $submission,
        ]);
    }

    /**
     * Upload CQ answer.
     */
    public function uploadCqAnswer(Request $request, Exam $exam)
    {
        $student = $this->getStudent();
        
        if (!$student || $exam->batch_id !== $student->batch_id) {
            abort(403, 'Unauthorized access to this exam.');
        }

        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $errors = $this->examService->validateCqFiles($request->file('files'));
        
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        $submission = $this->examService->submitCqAnswer($student, $exam, $request->file('files'));

        return redirect()->route('student.cq-submission', $submission->id)
            ->with('success', 'Answer uploaded successfully!');
    }

    /**
     * View CQ submission status.
     */
    public function viewCqSubmission(CqSubmission $submission)
    {
        $student = $this->getStudent();
        
        if (!$student || $submission->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this submission.');
        }

        $submission->load('exam');

        return view('student.cq-submission', [
            'student' => $student,
            'submission' => $submission,
        ]);
    }

    /**
     * View all results with filtering.
     */
    public function results(Request $request)
    {
        $student = $this->getStudent();
        
        if (!$student) {
            // For admin users without student profile, show all results
            if (Auth::user()->isAdmin()) {
                $filters = $request->only(['exam_type', 'from_date', 'to_date']);
                
                $query = ExamResult::with(['student', 'exam']);
                
                if (!empty($filters['exam_type'])) {
                    $query->whereHas('exam', function($q) use ($filters) {
                        $q->where('type', $filters['exam_type']);
                    });
                }
                
                if (!empty($filters['from_date'])) {
                    $query->whereDate('created_at', '>=', $filters['from_date']);
                }
                
                if (!empty($filters['to_date'])) {
                    $query->whereDate('created_at', '<=', $filters['to_date']);
                }
                
                $results = $query->orderBy('created_at', 'desc')->paginate(20);
                
                // Calculate trends from all results
                $allResults = ExamResult::orderBy('created_at')->take(10)->get();
                $trends = [
                    'labels' => $allResults->map(fn($r) => $r->created_at->format('M d'))->toArray(),
                    'scores' => $allResults->map(fn($r) => $r->percentage)->toArray(),
                ];
                
                return view('student.results', [
                    'student' => null,
                    'results' => $results,
                    'trends' => $trends,
                    'filters' => $filters,
                ]);
            }
            return redirect()->route('student.dashboard')->with('error', 'Student profile not found.');
        }

        $filters = $request->only(['exam_type', 'from_date', 'to_date']);
        $results = $this->portalService->getResults($student, $filters);
        $trends = $this->portalService->getPerformanceTrends($student);

        return view('student.results', [
            'student' => $student,
            'results' => $results,
            'trends' => $trends,
            'filters' => $filters,
        ]);
    }

    /**
     * Download mark sheet.
     */
    public function downloadMarkSheet(ExamResult $result)
    {
        $student = $this->getStudent();
        
        if (!$student || $result->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this mark sheet.');
        }

        return $this->markSheetService->generateMarkSheet($student, $result->exam);
    }

    /**
     * Performance trends for charts.
     */
    public function performanceTrends()
    {
        $student = $this->getStudent();
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $trends = $this->portalService->getPerformanceTrends($student);

        return response()->json($trends);
    }
}

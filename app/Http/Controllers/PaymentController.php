<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Show payment form for course enrollment.
     * Task 12.1
     * Requirements: 11.1, 11.2
     */
    public function showForm(Course $course): View
    {
        // Load payment methods from config
        $paymentMethods = config('payment-methods.methods');
        
        return view('student.payment-form', compact('course', 'paymentMethods'));
    }

    /**
     * Submit payment with screenshot.
     * Task 12.3
     * Requirements: 12.1, 12.2, 12.3, 12.4
     */
    public function submit(Request $request): RedirectResponse
    {
        // Validate inputs
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'payment_method' => 'required|in:bkash,nagad,rocket,bank_transfer',
            'transaction_id' => 'required|string|max:255',
            'screenshot' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get authenticated student
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Store screenshot
        $screenshotPath = $request->file('screenshot')->store('payment-screenshots', 'public');

        // Create payment record with pending status
        $payment = Payment::create([
            'student_id' => $student->id,
            'course_id' => $validated['course_id'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'screenshot_path' => $screenshotPath,
            'amount' => $validated['amount'],
            'status' => Payment::STATUS_PENDING,
            'submitted_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('student.payment.dashboard')
            ->with('success', 'Payment submitted successfully. Your payment is under review.');
    }

    /**
     * Display list of pending payments for admin review.
     * Task 13.1
     * Requirements: 13.1
     */
    public function reviewList(): View
    {
        // Fetch all pending payments with relationships
        $pendingPayments = Payment::with(['student.user', 'course'])
            ->where('status', Payment::STATUS_PENDING)
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        return view('admin.payment-review', compact('pendingPayments'));
    }

    /**
     * Display payment detail for admin review.
     * Task 13.3
     * Requirements: 13.2
     */
    public function reviewDetail(Payment $payment): View
    {
        // Load relationships
        $payment->load(['student.user', 'course']);

        return view('admin.payment-detail', compact('payment'));
    }

    /**
     * Approve payment and enroll student in course.
     * Task 13.5
     * Requirements: 13.3
     */
    public function approve(Payment $payment, Request $request): RedirectResponse
    {
        // Validate payment is pending
        if (!$payment->isPending()) {
            return redirect()->back()->with('error', 'Payment has already been processed.');
        }

        // Update payment status
        $payment->update([
            'status' => Payment::STATUS_APPROVED,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // Add student to course batch
        // Find an active batch for the course or create one
        $batch = Batch::where('course_id', $payment->course_id)
            ->where('status', 'active')
            ->first();

        if (!$batch) {
            // Create a new batch if none exists
            $batch = Batch::create([
                'course_id' => $payment->course_id,
                'name' => 'Batch ' . date('Y-m'),
                'start_date' => now(),
                'status' => 'active',
            ]);
        }

        // Enroll student in batch
        $student = $payment->student;
        if ($student && !$student->batch_id) {
            $student->update(['batch_id' => $batch->id]);
        }

        // TODO: Send notification to student (email or SMS)

        return redirect()->route('payment.review.list')
            ->with('success', 'Payment approved and student enrolled successfully.');
    }

    /**
     * Reject payment.
     * Task 13.6
     * Requirements: 13.4
     */
    public function reject(Payment $payment, Request $request): RedirectResponse
    {
        // Validate payment is pending
        if (!$payment->isPending()) {
            return redirect()->back()->with('error', 'Payment has already been processed.');
        }

        // Validate admin notes are provided
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        // Update payment status
        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        // TODO: Send notification to student (email or SMS)

        return redirect()->route('payment.review.list')
            ->with('success', 'Payment rejected successfully.');
    }

    /**
     * Display student payment dashboard.
     * Task 14.1
     * Requirements: 14.1, 14.2, 14.3
     */
    public function dashboard(): View
    {
        // Get authenticated student
        $student = Auth::user()->student;
        
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Fetch all payments for student
        $payments = Payment::with('course')
            ->where('student_id', $student->id)
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Group payments by course
        $enrollments = [];
        $paymentsByCourse = $payments->groupBy('course_id');

        foreach ($paymentsByCourse as $courseId => $coursePayments) {
            $course = $coursePayments->first()->course;
            $totalFee = $course->price ?? 0;
            $amountDeposited = $coursePayments->where('status', Payment::STATUS_APPROVED)->sum('amount');
            $pendingAmount = $coursePayments->where('status', Payment::STATUS_PENDING)->sum('amount');

            $enrollments[] = [
                'course' => $course,
                'total_fee' => $totalFee,
                'amount_deposited' => $amountDeposited,
                'pending_amount' => $pendingAmount,
                'payments' => $coursePayments,
            ];
        }

        // Calculate summary
        $totalCourses = count($enrollments);
        $totalFees = collect($enrollments)->sum('total_fee');
        $totalPaid = $payments->where('status', Payment::STATUS_APPROVED)->sum('amount');
        $totalPending = $payments->where('status', Payment::STATUS_PENDING)->sum('amount');

        return view('student.payment-dashboard', compact(
            'enrollments',
            'payments',
            'totalCourses',
            'totalFees',
            'totalPaid',
            'totalPending'
        ));
    }
}

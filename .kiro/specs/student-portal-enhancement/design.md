# Design Document: Student Portal Enhancement

## Overview

This design enhances the existing Laravel-based student portal with improved exam-taking interfaces and a course enrollment system with payment verification. The implementation focuses on connecting existing models and controllers while adding new UI components and workflows. The system leverages existing Laravel Blade templates, Tailwind CSS, and database models (Student, Exam, ExamAttempt, ExamResult, Course, Batch, Payment).

The design prioritizes rapid implementation by reusing existing infrastructure and focusing on UI enhancements and workflow connections rather than building new foundational components.

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Student Portal (Blade Views)             │
├─────────────────────────────────────────────────────────────┤
│  Exam Interface  │  Course Browser  │  Payment Dashboard    │
│  - MCQ Exam      │  - Course List   │  - Payment History    │
│  - CQ Exam       │  - Buy Course    │  - Status Tracking    │
│  - Live Exam     │  - Enrollment    │  - Fee Summary        │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Controllers                       │
├─────────────────────────────────────────────────────────────┤
│  ExamController  │  CourseController  │  PaymentController  │
│  - takeExam()    │  - browse()        │  - submit()         │
│  - submitExam()  │  - enroll()        │  - review()         │
│  - viewResults() │  - purchase()      │  - approve()        │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    Eloquent Models                           │
├─────────────────────────────────────────────────────────────┤
│  Student  │  Exam  │  ExamAttempt  │  Course  │  Payment   │
│  ExamResult  │  Batch  │  ExamQuestion  │  Answer          │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                      MySQL Database                          │
└─────────────────────────────────────────────────────────────┘
```

### Component Interaction Flow

**Exam Taking Flow:**
1. Student accesses exam list → ExamController filters available exams
2. Student clicks "Take Exam" → ExamController validates time and creates ExamAttempt
3. Exam interface loads → JavaScript handles timer, navigation, anti-cheating
4. Student submits → ExamController saves answers and creates ExamResult
5. Results display → ExamController retrieves results with analysis

**Payment Flow:**
1. Student browses courses → CourseController shows unenrolled courses
2. Student clicks "Buy Course" → PaymentController shows payment form
3. Student uploads screenshot → PaymentController creates Payment record (pending)
4. Admin reviews → PaymentController displays pending payments
5. Admin approves → PaymentController adds student to Batch

## Components and Interfaces

### 1. Exam Access Control Middleware

**Purpose:** Restrict student access to exam management functions

**Implementation:**
- Create `StudentExamAccessMiddleware` that checks user role
- Apply to exam routes to prevent students from accessing edit/view admin functions
- Redirect unauthorized access attempts to exam-taking interface

**Routes to protect:**
```php
// Only allow students to access:
Route::get('/exams/{exam}/take', [ExamController::class, 'take']);
Route::post('/exams/{exam}/submit', [ExamController::class, 'submit']);
Route::get('/exams/{exam}/results', [ExamController::class, 'results']);

// Block students from:
// /exams/{exam}/edit
// /exams/{exam}/view
// /exams/create
```

### 2. MCQ Exam Interface Component

**Blade View:** `resources/views/student/mcq-exam.blade.php`

**Data Structure:**
```php
[
    'exam' => Exam,
    'attempt' => ExamAttempt,
    'questions' => Collection<ExamQuestion>,
    'timeRemaining' => int (seconds),
    'savedAnswers' => array
]
```

**JavaScript Functionality:**
- Timer countdown with auto-submit at zero
- Question navigation (next/previous/jump to question)
- Auto-save answers via AJAX every 30 seconds
- Progress indicator showing answered questions
- Fullscreen mode enforcement
- Tab switch detection and logging

**Controller Method:**
```php
ExamController::takeMCQ(Exam $exam)
- Validate exam time window
- Create or retrieve ExamAttempt
- Load questions with options
- Calculate time remaining
- Return view with data
```

### 3. CQ Exam Interface Component

**Blade View:** `resources/views/student/cq-exam.blade.php`

**Data Structure:**
```php
[
    'exam' => Exam,
    'attempt' => ExamAttempt,
    'questions' => Collection<ExamQuestion>,
    'timeRemaining' => int (seconds)
]
```

**Features:**
- Rich text editor for each question (TinyMCE or similar)
- Screenshot upload field per question
- File validation (max 5MB, jpg/png/pdf)
- Auto-save functionality
- Timer and anti-cheating measures

**Controller Method:**
```php
ExamController::takeCQ(Exam $exam)
- Validate exam time window
- Create or retrieve ExamAttempt
- Load CQ questions
- Return view with data

ExamController::uploadScreenshot(Request $request)
- Validate file upload
- Store in storage/app/exam-screenshots
- Link to ExamAttempt
- Return success response
```

### 4. Live Exam Interface Component

**Blade View:** `resources/views/student/live-exam.blade.php`

**Implementation:**
- Similar to MCQ interface but with real-time indicators
- WebSocket or polling for live status updates
- Display "LIVE" badge prominently
- Synchronized timer across all participants

**Controller Method:**
```php
ExamController::takeLive(Exam $exam)
- Validate exam is currently live
- Create ExamAttempt
- Load questions
- Return view with live status
```

### 5. Exam Time Validation Service

**Class:** `App\Services\ExamTimeValidator`

**Methods:**
```php
canStartExam(Exam $exam): bool
- Check current time >= exam start_time
- Check current time <= exam end_time
- Return boolean

getTimeStatus(Exam $exam): string
- Return 'not_started', 'active', or 'ended'

getRemainingTime(ExamAttempt $attempt): int
- Calculate seconds remaining
- Consider exam duration and start time
- Return 0 if expired
```

**Integration:**
- Called in ExamController before allowing exam access
- Used in Blade views to display appropriate messages
- Scheduled job to auto-submit expired attempts

### 6. Anti-Cheating Detection System

**JavaScript Module:** `resources/js/exam-monitor.js`

**Features:**
```javascript
class ExamMonitor {
    enableFullscreen()
    detectFullscreenExit()
    detectTabSwitch()
    logEvent(eventType, timestamp)
    sendToServer(events)
}
```

**Database Storage:**
- Add `cheating_events` JSON column to `exam_attempts` table
- Store array of events: `[{type: 'tab_switch', timestamp: '...'}]`

**Controller Method:**
```php
ExamController::logCheatingEvent(Request $request)
- Validate exam attempt ownership
- Append event to cheating_events JSON
- Return success response
```

### 7. Exam Results Display Component

**Blade View:** `resources/views/student/exam-results.blade.php`

**Data Structure:**
```php
[
    'result' => ExamResult,
    'attempt' => ExamAttempt,
    'exam' => Exam,
    'questions' => Collection with answers and correct answers,
    'performance' => [
        'score' => int,
        'percentage' => float,
        'time_taken' => int (seconds),
        'accuracy' => float,
        'rank' => int
    ]
]
```

**Display Sections:**
- Header: Score, percentage, pass/fail status
- Performance metrics: Time taken, accuracy, rank
- Question breakdown: Each question with student answer and correct answer
- For MCQ: Highlight correct (green) and incorrect (red) answers
- For CQ: Display submitted text and screenshots

**Controller Method:**
```php
ExamController::viewResults(Exam $exam)
- Retrieve ExamResult for authenticated student
- Load related questions and answers
- Calculate performance metrics
- Fetch leaderboard rank
- Return view with data
```

### 8. PDF Export Service

**Class:** `App\Services\ExamResultPdfGenerator`

**Dependencies:** Use `barryvdh/laravel-dompdf` package

**Method:**
```php
generate(ExamResult $result): string
- Load result data with questions and answers
- Render Blade view: views/pdf/exam-result.blade.php
- Generate PDF using dompdf
- Return file path or download response
```

**PDF Template Structure:**
- Student information header
- Exam details (title, date, duration)
- Score summary with visual indicators
- Question-by-question breakdown
- Performance analysis section
- Footer with generation timestamp

**Controller Method:**
```php
ExamController::downloadResultPdf(Exam $exam)
- Retrieve ExamResult
- Call PdfGenerator service
- Return PDF download response
```

### 9. Exam Leaderboard Component

**Blade View:** `resources/views/student/exam-leaderboard.blade.php`

**Data Structure:**
```php
[
    'exam' => Exam,
    'leaderboard' => Collection<[
        'rank' => int,
        'student_name' => string,
        'score' => int,
        'percentage' => float,
        'time_taken' => int,
        'is_current_user' => bool
    ]>,
    'currentUserRank' => int
]
```

**Controller Method:**
```php
ExamController::leaderboard(Exam $exam)
- Query ExamResults for this exam
- Order by score DESC, time_taken ASC
- Add rank numbers
- Highlight current user
- Return view with data
```

**Display Features:**
- Top 10 or all students (configurable)
- Highlight current student's row
- Show rank, name, score, time
- Medal icons for top 3

### 10. Course Browsing Component

**Blade View:** `resources/views/student/courses.blade.php`

**Data Structure:**
```php
[
    'courses' => Collection<Course>,
    'enrolledCourseIds' => array,
    'pendingPaymentCourseIds' => array
]
```

**Display Logic:**
- Show all courses with overview information
- For enrolled courses: Show "Enrolled" badge, hide "Buy Course" button
- For unenrolled courses: Show "Buy Course" button
- For courses with pending payment: Show "Payment Pending" status

**Controller Method:**
```php
CourseController::browse()
- Fetch all active courses
- Get authenticated student's enrollments
- Get pending payments
- Return view with data
```

### 11. Payment Method Selection Component

**Blade View:** `resources/views/student/payment-form.blade.php`

**Data Structure:**
```php
[
    'course' => Course,
    'paymentMethods' => [
        ['name' => 'bKash', 'number' => '01XXXXXXXXX', 'instructions' => '...'],
        ['name' => 'Nagad', 'number' => '01XXXXXXXXX', 'instructions' => '...'],
        ['name' => 'Rocket', 'number' => '01XXXXXXXXX', 'instructions' => '...'],
        ['name' => 'Bank Transfer', 'details' => '...', 'instructions' => '...']
    ]
]
```

**Form Fields:**
- Course (read-only display)
- Payment method (radio buttons)
- Transaction ID (text input)
- Payment screenshot (file upload)
- Amount paid (number input)
- Notes (textarea, optional)

**Controller Method:**
```php
PaymentController::showForm(Course $course)
- Load course details
- Load payment methods from config
- Return view with data
```

### 12. Payment Screenshot Upload Handler

**Controller Method:**
```php
PaymentController::submit(Request $request)
- Validate inputs:
  - course_id: required, exists
  - payment_method: required, in allowed list
  - transaction_id: required, string
  - screenshot: required, image, max:5MB
  - amount: required, numeric
- Store screenshot in storage/app/payment-screenshots
- Create Payment record:
  - student_id
  - course_id
  - payment_method
  - transaction_id
  - screenshot_path
  - amount
  - status: 'pending'
  - submitted_at: now()
- Redirect to payment dashboard with success message
```

**Payment Model:**
```php
class Payment extends Model {
    protected $fillable = [
        'student_id', 'course_id', 'payment_method',
        'transaction_id', 'screenshot_path', 'amount',
        'status', 'submitted_at', 'reviewed_at',
        'reviewed_by', 'admin_notes'
    ];
    
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
}
```

### 13. Admin Payment Review Interface

**Blade View:** `resources/views/admin/payment-review.blade.php`

**Data Structure:**
```php
[
    'pendingPayments' => Collection<Payment>,
    'payment' => Payment (for detail view),
    'student' => Student,
    'course' => Course
]
```

**Display Features:**
- List view: All pending payments with student name, course, amount, date
- Detail view: Full payment information with screenshot preview
- Action buttons: Approve, Reject
- Admin notes field

**Controller Methods:**
```php
PaymentController::reviewList()
- Fetch all pending payments
- Eager load student and course
- Return view with data

PaymentController::reviewDetail(Payment $payment)
- Load payment with relationships
- Return view with data

PaymentController::approve(Payment $payment, Request $request)
- Validate payment is pending
- Update payment status to 'approved'
- Add student to course batch
- Record reviewer and timestamp
- Send notification to student
- Redirect with success message

PaymentController::reject(Payment $payment, Request $request)
- Validate payment is pending
- Update payment status to 'rejected'
- Save admin notes
- Record reviewer and timestamp
- Send notification to student
- Redirect with success message
```

### 14. Student Payment Dashboard Component

**Blade View:** `resources/views/student/payment-dashboard.blade.php`

**Data Structure:**
```php
[
    'enrollments' => Collection<[
        'course' => Course,
        'total_fee' => float,
        'amount_deposited' => float,
        'pending_amount' => float,
        'payments' => Collection<Payment>
    ]>,
    'paymentHistory' => Collection<Payment>
]
```

**Display Sections:**
1. **Summary Cards:**
   - Total courses enrolled
   - Total fees
   - Total paid
   - Total pending

2. **Course-wise Breakdown:**
   - Course name
   - Total fee
   - Amount deposited (approved payments)
   - Pending payments (under review)
   - Payment status indicator

3. **Payment History Table:**
   - Date
   - Course
   - Payment method
   - Transaction ID
   - Amount
   - Status (badge: pending/approved/rejected)
   - Screenshot link
   - Admin notes (if rejected)

**Controller Method:**
```php
PaymentController::dashboard()
- Fetch student's enrollments with courses
- Fetch all payments for student
- Calculate totals and breakdowns
- Group payments by course
- Return view with data
```

## Data Models

### Existing Models (Leverage as-is)

**Student Model:**
```php
class Student extends Model {
    // Existing relationships
    public function examAttempts()
    public function examResults()
    public function batches()
    public function courses()
}
```

**Exam Model:**
```php
class Exam extends Model {
    // Existing fields
    protected $fillable = [
        'title', 'type', 'start_time', 'end_time',
        'duration', 'total_marks', 'passing_marks'
    ];
    
    // Existing relationships
    public function questions()
    public function attempts()
    public function results()
}
```

**ExamAttempt Model:**
```php
class ExamAttempt extends Model {
    // Add new fields via migration
    protected $fillable = [
        'student_id', 'exam_id', 'started_at',
        'submitted_at', 'answers', 'cheating_events'
    ];
    
    protected $casts = [
        'answers' => 'array',
        'cheating_events' => 'array'
    ];
}
```

**ExamResult Model:**
```php
class ExamResult extends Model {
    // Existing fields
    protected $fillable = [
        'exam_attempt_id', 'student_id', 'exam_id',
        'score', 'percentage', 'time_taken', 'passed'
    ];
}
```

**Course Model:**
```php
class Course extends Model {
    // Existing fields
    protected $fillable = [
        'title', 'description', 'instructor_id',
        'fee', 'duration', 'status'
    ];
    
    // Existing relationships
    public function batches()
    public function students()
}
```

**Batch Model:**
```php
class Batch extends Model {
    // Existing fields
    protected $fillable = [
        'course_id', 'name', 'start_date', 'end_date'
    ];
    
    // Existing relationships
    public function course()
    public function students()
}
```

### New Model: Payment

**Migration:**
```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained();
    $table->foreignId('course_id')->constrained();
    $table->string('payment_method');
    $table->string('transaction_id');
    $table->string('screenshot_path');
    $table->decimal('amount', 10, 2);
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamp('submitted_at');
    $table->timestamp('reviewed_at')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('users');
    $table->text('admin_notes')->nullable();
    $table->timestamps();
});
```

**Model:**
```php
class Payment extends Model {
    protected $fillable = [
        'student_id', 'course_id', 'payment_method',
        'transaction_id', 'screenshot_path', 'amount',
        'status', 'submitted_at', 'reviewed_at',
        'reviewed_by', 'admin_notes'
    ];
    
    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'amount' => 'decimal:2'
    ];
    
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
    
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
```

### Database Migrations Needed

1. **Add cheating_events to exam_attempts:**
```php
Schema::table('exam_attempts', function (Blueprint $table) {
    $table->json('cheating_events')->nullable();
});
```

2. **Create payments table:** (shown above)

3. **Add screenshot_path to exam_attempts (for CQ exams):**
```php
Schema::table('exam_attempts', function (Blueprint $table) {
    $table->json('screenshots')->nullable();
});
```


## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property Reflection

After analyzing all acceptance criteria, several properties can be consolidated:
- Properties 1.1 and 1.3 both test UI rendering for students - can be combined into one comprehensive property
- Properties 10.3 and 10.4 are inverse conditions - can be combined into one property about conditional button display
- Properties 5.1 and 5.2 both test time-based access control - can be combined into one property
- Properties 7.1, 7.2, 7.3, 7.4 all test results display - can be combined into fewer comprehensive properties
- Properties 9.1, 9.2, 9.3 all test leaderboard display - can be combined into one comprehensive property

### Access Control Properties

**Property 1: Student exam action restriction**
*For any* student viewing the exam list, the rendered interface should only contain "Take Exam" actions and should not contain any administrative actions like "Edit" or "View" or "Delete".
**Validates: Requirements 1.1, 1.3**

**Property 2: Student URL access protection**
*For any* exam and any student attempting to access administrative URLs (edit, view, manage), the system should redirect to the exam-taking interface or deny access.
**Validates: Requirements 1.2**

### MCQ Exam Properties

**Property 3: Answer persistence**
*For any* MCQ exam and any answer selection, saving the answer should result in the answer being retrievable from storage.
**Validates: Requirements 2.4**

**Property 4: Auto-submit on timer expiration**
*For any* active exam attempt, when the timer reaches zero, the system should automatically submit the attempt without user action.
**Validates: Requirements 2.2**

**Property 5: Progress tracking accuracy**
*For any* exam attempt, the progress indicator should accurately reflect the count of answered versus unanswered questions.
**Validates: Requirements 2.5**

### CQ Exam Properties

**Property 6: Screenshot upload validation**
*For any* file upload attempt, files with invalid types (not jpg/png/pdf) or exceeding size limits should be rejected, and valid files should be accepted.
**Validates: Requirements 3.3**

**Property 7: Screenshot storage linkage**
*For any* successfully uploaded screenshot, the file should be stored and linked to the correct exam attempt.
**Validates: Requirements 3.4**

### Live Exam Properties

**Property 8: Real-time response synchronization**
*For any* live exam response submission, the response should be synchronized and retrievable in real-time.
**Validates: Requirements 4.2**

### Time Validation Properties

**Property 9: Time-based access control**
*For any* exam, students should only be able to start the exam when current time is between start_time and end_time, and access should be denied outside this window.
**Validates: Requirements 5.1, 5.2**

**Property 10: Automatic submission at end time**
*For any* active exam attempt, when the exam end time is reached, the system should automatically submit the attempt.
**Validates: Requirements 5.3**

### Anti-Cheating Properties

**Property 11: Fullscreen exit logging**
*For any* fullscreen exit event during an exam, the event should be logged with a timestamp in the exam attempt's cheating_events.
**Validates: Requirements 6.2**

**Property 12: Tab switch logging**
*For any* tab switch event during an exam, the event should be logged with a timestamp in the exam attempt's cheating_events.
**Validates: Requirements 6.3**

**Property 13: Cheating event persistence**
*For any* cheating detection event (fullscreen exit or tab switch), the event should be stored with the exam attempt and be retrievable.
**Validates: Requirements 6.4**

### Results Display Properties

**Property 14: Results completeness**
*For any* graded exam, the results view should display score, percentage, question-by-question breakdown with correct answers, and performance metrics (time taken, accuracy).
**Validates: Requirements 7.1, 7.2, 7.4**

**Property 15: MCQ answer highlighting**
*For any* MCQ exam result, correct answers should be visually distinguished from incorrect answers in the results view.
**Validates: Requirements 7.3**

### PDF Export Properties

**Property 16: PDF generation completeness**
*For any* exam result, the generated PDF should contain student information, exam details, scores, and performance analysis.
**Validates: Requirements 8.2, 8.3**

### Leaderboard Properties

**Property 17: Leaderboard accuracy and highlighting**
*For any* completed exam, the leaderboard should display all students ordered by score (descending) and time (ascending), showing rank, name, score, and time, with the current student's row highlighted.
**Validates: Requirements 9.1, 9.2, 9.3**

### Course Browsing Properties

**Property 18: Course information display**
*For any* course in the system, the course browser should display title, description, instructor, and fee.
**Validates: Requirements 10.1, 10.2**

**Property 19: Conditional buy button display**
*For any* course, the "Buy Course" button should be visible if and only if the student is not enrolled in that course.
**Validates: Requirements 10.3, 10.4**

### Payment Method Properties

**Property 20: Payment method instructions**
*For any* selected payment method, the system should display corresponding payment instructions.
**Validates: Requirements 11.3**

### Payment Upload Properties

**Property 21: Screenshot requirement enforcement**
*For any* payment submission attempt without a screenshot, the submission should be rejected.
**Validates: Requirements 12.1**

**Property 22: Payment file format validation**
*For any* payment screenshot upload, only JPG, PNG, and PDF formats should be accepted, and other formats should be rejected.
**Validates: Requirements 12.2, 12.3**

**Property 23: Payment record creation**
*For any* successful payment submission, a payment record with status 'pending' should be created and linked to the student and course.
**Validates: Requirements 12.4**

### Payment Review Properties

**Property 24: Pending payment visibility**
*For any* submitted payment with pending status, it should appear in the admin pending payments list.
**Validates: Requirements 13.1**

**Property 25: Payment review information completeness**
*For any* payment being reviewed, the admin interface should display the screenshot, student details, and course information.
**Validates: Requirements 13.2**

**Property 26: Payment approval enrollment**
*For any* payment approval action, the student should be added to the course batch and the payment status should be updated to 'approved'.
**Validates: Requirements 13.3**

**Property 27: Payment rejection handling**
*For any* payment rejection action, the payment status should be updated to 'rejected' and the student should be notified.
**Validates: Requirements 13.4**

### Payment Dashboard Properties

**Property 28: Payment dashboard completeness**
*For any* student, the payment dashboard should display all payment records with their status, total course fees, amount deposited, and pending payments.
**Validates: Requirements 14.1, 14.2, 14.3, 14.4**

**Property 29: Payment status support**
*For any* payment record, the status should be one of: pending, approved, or rejected.
**Validates: Requirements 14.5**

## Error Handling

### Exam Access Errors

**Unauthorized Access:**
- Catch: Student attempting to access admin exam functions
- Response: Redirect to student exam list with message "Access denied"
- Log: Security event with student ID and attempted URL

**Time Window Violations:**
- Catch: Exam start before start_time or after end_time
- Response: Display message with exam schedule information
- Log: Access attempt with timestamp

**Expired Exam Attempt:**
- Catch: Student trying to continue expired exam
- Response: Auto-submit and redirect to results
- Log: Auto-submission event

### File Upload Errors

**Invalid File Type:**
- Catch: Upload of non-image/non-PDF file
- Response: Display error "Please upload JPG, PNG, or PDF only"
- Validation: Check MIME type and extension

**File Size Exceeded:**
- Catch: Upload exceeding 5MB limit
- Response: Display error "File size must be under 5MB"
- Validation: Check file size before processing

**Upload Failure:**
- Catch: Storage write failure
- Response: Display error "Upload failed, please try again"
- Log: Error with details for debugging
- Rollback: Delete partial uploads

### Payment Processing Errors

**Duplicate Payment:**
- Catch: Student submitting multiple payments for same course
- Response: Display warning "Payment already submitted for this course"
- Allow: Multiple payments if previous was rejected

**Invalid Course:**
- Catch: Payment for non-existent or inactive course
- Response: Display error "Course not available"
- Validation: Check course exists and is active

**Already Enrolled:**
- Catch: Payment for course student is already enrolled in
- Response: Display message "You are already enrolled in this course"
- Redirect: To course dashboard

### Database Errors

**Connection Failure:**
- Catch: Database connection lost
- Response: Display error "Service temporarily unavailable"
- Retry: Implement retry logic for transient failures
- Log: Critical error for monitoring

**Constraint Violations:**
- Catch: Foreign key or unique constraint violations
- Response: Display user-friendly error message
- Log: Error details for debugging
- Rollback: Transaction rollback

### Session and Authentication Errors

**Session Expired:**
- Catch: Session timeout during exam
- Response: Save current state, redirect to login
- Restore: Resume exam after re-authentication

**Concurrent Exam Attempts:**
- Catch: Student trying to start exam while another attempt is active
- Response: Display error "You have an active exam attempt"
- Provide: Link to resume existing attempt

## Testing Strategy

This implementation focuses on rapid deployment by connecting existing components. Testing will be primarily manual and integration-focused rather than comprehensive unit testing.

### Manual Testing Approach

**Exam Interface Testing:**
- Manually test each exam type (MCQ, CQ, Live) with different scenarios
- Verify timer functionality and auto-submit behavior
- Test anti-cheating detection by switching tabs and exiting fullscreen
- Validate time window restrictions with past, current, and future exams
- Test results display with various score ranges

**Payment Workflow Testing:**
- Test complete payment flow from course selection to approval
- Upload various file types and sizes to verify validation
- Test admin review interface with approve and reject actions
- Verify enrollment occurs after payment approval
- Test payment dashboard with multiple payment states

**Access Control Testing:**
- Attempt to access admin URLs as student
- Verify only "Take Exam" actions are visible to students
- Test middleware protection on all restricted routes

### Integration Testing

**Database Integration:**
- Verify all CRUD operations work correctly
- Test relationships between models (Student, Exam, Payment, Course)
- Validate data integrity after payment approval

**File Storage Integration:**
- Test screenshot and PDF storage
- Verify file retrieval and display
- Test cleanup of orphaned files

**UI Integration:**
- Test Blade template rendering with various data states
- Verify Tailwind CSS styling is consistent
- Test responsive design on different screen sizes

### Browser Testing

**JavaScript Functionality:**
- Test timer countdown in multiple browsers
- Verify AJAX auto-save works correctly
- Test fullscreen API compatibility
- Verify tab switch detection across browsers

**Cross-Browser Compatibility:**
- Test on Chrome, Firefox, Safari, Edge
- Verify file upload works on all browsers
- Test PDF generation and download

### User Acceptance Testing

**Student Workflow:**
- Have test students complete full exam-taking flow
- Gather feedback on UI/UX
- Test payment submission and tracking

**Admin Workflow:**
- Have test admins review and process payments
- Verify admin interface is intuitive
- Test bulk payment processing if needed

### Performance Testing

**Load Testing:**
- Test with multiple concurrent exam attempts
- Verify auto-submit works under load
- Test file upload with multiple simultaneous uploads

**Database Performance:**
- Monitor query performance with large datasets
- Optimize slow queries if identified
- Test pagination on large result sets

### Security Testing

**Access Control:**
- Verify students cannot access admin functions
- Test direct URL access attempts
- Validate middleware protection

**File Upload Security:**
- Test malicious file upload attempts
- Verify file type validation cannot be bypassed
- Test path traversal vulnerabilities

**SQL Injection:**
- Test input fields for SQL injection vulnerabilities
- Verify Eloquent ORM provides protection
- Test raw queries if any exist

### Deployment Testing

**Staging Environment:**
- Deploy to staging and run full test suite
- Test with production-like data volumes
- Verify environment configuration

**Production Deployment:**
- Use blue-green deployment or similar strategy
- Monitor error logs during initial rollout
- Have rollback plan ready

### Testing Priorities

Given the focus on rapid implementation:

1. **Critical Path Testing** (Must have):
   - Exam taking and submission
   - Payment submission and approval
   - Access control and security

2. **Important Testing** (Should have):
   - Results display and PDF export
   - Payment dashboard
   - Anti-cheating detection

3. **Nice to Have Testing** (Could have):
   - Edge cases and error scenarios
   - Performance optimization
   - Advanced UI interactions

### Testing Tools

**Manual Testing:**
- Browser developer tools for debugging
- Laravel Telescope for request monitoring
- Database query logging

**Automated Testing (if time permits):**
- Laravel Dusk for browser testing
- PHPUnit for basic unit tests
- Postman for API endpoint testing

### Test Data

**Seed Data:**
- Create seed data for courses, exams, students
- Include various exam types and states
- Create test payment records in different states

**Test Accounts:**
- Student accounts with various enrollment states
- Admin accounts for payment review
- Test accounts with edge case scenarios

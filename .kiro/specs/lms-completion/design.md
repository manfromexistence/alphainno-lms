# Design Document: LMS Completion

## Overview

This design document outlines the technical architecture and implementation approach for completing all missing features in the Laravel LMS project. The implementation leverages Laravel 11's service pattern, existing models (CourseMaterial, ClassSchedule, TeacherSalary), and Blade views with Tailwind CSS.

The design is organized into logical modules:
1. **Student Portal Module** - Student-facing dashboard, materials, exams, results, payments
2. **Exam Engine Module** - MCQ/CQ exam delivery, timing, auto-submission, grading
3. **Accounts Module** - Income, expenses, financial reports
4. **Inventory Module** - Stock management, alerts, purchase history
5. **Communication Module** - Email notifications, announcements, group links
6. **Admin Enhancements** - Materials CRUD, schedules, salary, backup, logging, import

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Frontend (Blade + Tailwind)               │
├─────────────────────────────────────────────────────────────────┤
│  Student Portal Views  │  Admin Dashboard Views  │  Public Views │
└─────────────────────────────────────────────────────────────────┘
                                    │
┌─────────────────────────────────────────────────────────────────┐
│                         Controllers                              │
├─────────────────────────────────────────────────────────────────┤
│ StudentPortalController │ ExamController │ AccountController     │
│ InventoryController     │ ScheduleController │ SalaryController  │
│ AnnouncementController  │ BackupController │ ImportController    │
└─────────────────────────────────────────────────────────────────┘
                                    │
┌─────────────────────────────────────────────────────────────────┐
│                          Services                                │
├─────────────────────────────────────────────────────────────────┤
│ StudentPortalService │ ExamTakingService │ AccountService        │
│ InventoryService     │ EmailNotificationService │ BackupService  │
│ ActivityLogService   │ ImportService │ MarkSheetService          │
└─────────────────────────────────────────────────────────────────┘
                                    │
┌─────────────────────────────────────────────────────────────────┐
│                           Models                                 │
├─────────────────────────────────────────────────────────────────┤
│ Existing: Student, Exam, ExamResult, Payment, CourseMaterial,   │
│           ClassSchedule, TeacherSalary, Batch, Course           │
│ New: Expense, Income, InventoryItem, InventoryTransaction,      │
│      Announcement, ActivityLog, ExamAttempt, CqSubmission       │
└─────────────────────────────────────────────────────────────────┘
```

### Module Organization

```
app/
├── Http/Controllers/
│   ├── StudentPortalController.php      # Student-facing portal
│   ├── Admin/
│   │   ├── AccountController.php        # Enhanced with full CRUD
│   │   ├── InventoryController.php      # New
│   │   ├── ScheduleController.php       # New - Class schedules
│   │   ├── SalaryController.php         # New - Teacher salary
│   │   ├── MaterialController.php       # New - Course materials CRUD
│   │   ├── AnnouncementController.php   # New
│   │   ├── BackupController.php         # New
│   │   └── ImportController.php         # New
├── Services/
│   ├── StudentPortalService.php         # New
│   ├── ExamTakingService.php            # New
│   ├── AccountService.php               # New
│   ├── InventoryService.php             # New
│   ├── EmailNotificationService.php     # New
│   ├── MarkSheetService.php             # New
│   ├── BackupService.php                # New
│   ├── ActivityLogService.php           # New
│   └── ImportService.php                # New
├── Models/
│   ├── Expense.php                      # New
│   ├── Income.php                       # New
│   ├── InventoryItem.php                # New
│   ├── InventoryTransaction.php         # New
│   ├── Announcement.php                 # New
│   ├── ActivityLog.php                  # New
│   ├── ExamAttempt.php                  # New
│   └── CqSubmission.php                 # New
```

## Components and Interfaces

### 1. Student Portal Controller

```php
class StudentPortalController extends Controller
{
    public function __construct(
        protected StudentPortalService $portalService,
        protected ExamTakingService $examService
    ) {}

    // Dashboard
    public function dashboard(): View;
    
    // Materials
    public function materials(): View;
    public function downloadMaterial(CourseMaterial $material): Response;
    
    // Exams
    public function exams(): View;
    public function startExam(Exam $exam): View;
    public function submitExam(Request $request, Exam $exam): RedirectResponse;
    public function examResult(ExamResult $result): View;
    
    // CQ Exams
    public function uploadCqAnswer(Request $request, Exam $exam): RedirectResponse;
    
    // Results
    public function results(): View;
    public function downloadMarkSheet(ExamResult $result): Response;
    
    // Payments
    public function payments(): View;
    public function downloadReceipt(Payment $payment): Response;
    
    // Schedule
    public function schedule(): View;
}
```

### 2. Exam Taking Service

```php
class ExamTakingService
{
    // Start exam attempt
    public function startAttempt(Student $student, Exam $exam): ExamAttempt;
    
    // Save answer (auto-save)
    public function saveAnswer(ExamAttempt $attempt, int $questionId, string $answer): void;
    
    // Submit exam
    public function submitExam(ExamAttempt $attempt): ExamResult;
    
    // Auto-submit on timeout
    public function autoSubmitExpired(): int;
    
    // Calculate MCQ score
    public function calculateMcqScore(ExamAttempt $attempt): array;
    
    // Get exam with remaining time
    public function getExamWithTimer(ExamAttempt $attempt): array;
    
    // CQ submission
    public function submitCqAnswer(Student $student, Exam $exam, array $files): CqSubmission;
    
    // Evaluate CQ
    public function evaluateCq(CqSubmission $submission, int $marks, string $feedback): void;
}
```

### 3. Account Service

```php
class AccountService
{
    // Expenses
    public function createExpense(array $data): Expense;
    public function updateExpense(Expense $expense, array $data): Expense;
    public function deleteExpense(Expense $expense): void;
    public function getExpenses(array $filters): LengthAwarePaginator;
    
    // Income
    public function recordIncome(array $data): Income;
    public function getIncome(array $filters): LengthAwarePaginator;
    public function recordPaymentIncome(Payment $payment): Income;
    
    // Reports
    public function getDailySummary(Carbon $date): array;
    public function getMonthlySummary(int $year, int $month): array;
    public function getFinancialReport(Carbon $startDate, Carbon $endDate): array;
    public function exportToExcel(array $filters): string;
    public function exportToPdf(array $filters): string;
}
```

### 4. Inventory Service

```php
class InventoryService
{
    public function createItem(array $data): InventoryItem;
    public function updateItem(InventoryItem $item, array $data): InventoryItem;
    public function deleteItem(InventoryItem $item): void;
    public function getItems(array $filters): LengthAwarePaginator;
    
    // Stock operations
    public function recordPurchase(InventoryItem $item, array $data): InventoryTransaction;
    public function recordUsage(InventoryItem $item, array $data): InventoryTransaction;
    public function getLowStockItems(int $threshold = 10): Collection;
    public function getTransactionHistory(InventoryItem $item): Collection;
    
    // Reports
    public function getInventoryReport(): array;
}
```

### 5. Email Notification Service

```php
class EmailNotificationService
{
    public function sendPaymentConfirmation(Payment $payment): void;
    public function sendResultNotification(ExamResult $result): void;
    public function sendAttendanceAlert(Student $student, float $percentage): void;
    public function sendBulkEmail(array $recipients, string $subject, string $body): void;
    
    // Template management
    public function getTemplate(string $type): ?string;
    public function renderTemplate(string $template, array $variables): string;
}
```

### 6. Mark Sheet Service

```php
class MarkSheetService
{
    public function generateMarkSheet(Student $student, ?Exam $exam = null): string;
    public function generateBatchMarkSheet(Batch $batch, Exam $exam): string;
    
    // PDF generation
    protected function renderPdf(array $data): string;
}
```

### 7. Backup Service

```php
class BackupService
{
    public function createBackup(): string;
    public function restoreBackup(string $filename): bool;
    public function getBackupList(): Collection;
    public function deleteBackup(string $filename): bool;
    public function downloadBackup(string $filename): Response;
}
```

### 8. Activity Log Service

```php
class ActivityLogService
{
    public function log(string $action, ?Model $model = null, ?array $changes = null): ActivityLog;
    public function getLogs(array $filters): LengthAwarePaginator;
    
    // Auto-logging via model observers
    public function logCreate(Model $model): void;
    public function logUpdate(Model $model, array $changes): void;
    public function logDelete(Model $model): void;
}
```

### 9. Import Service

```php
class ImportService
{
    public function parseFile(UploadedFile $file): array;
    public function validateData(array $data, string $type): array;
    public function previewImport(array $data, string $type): array;
    public function executeImport(array $data, string $type, array $mapping): ImportResult;
    
    // Supported types
    public function importStudents(array $data, array $mapping): int;
}
```

## Data Models

### New Models

#### Expense Model
```php
class Expense extends Model
{
    protected $fillable = [
        'category',      // rent, salary, bills, advertisement, furniture, paper, stationary
        'amount',
        'description',
        'expense_date',
        'receipt_number',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date'
    ];

    public function creator(): BelongsTo;
    
    public const CATEGORIES = [
        'rent', 'salary', 'bills', 'advertisement', 
        'furniture', 'paper', 'stationary', 'other'
    ];
}
```

#### Income Model
```php
class Income extends Model
{
    protected $fillable = [
        'category',      // admission, tuition, materials, other
        'amount',
        'description',
        'income_date',
        'student_id',    // nullable, for student-related income
        'payment_id',    // nullable, links to payment
        'reference',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'income_date' => 'date'
    ];

    public function student(): BelongsTo;
    public function payment(): BelongsTo;
    public function creator(): BelongsTo;
}
```

#### InventoryItem Model
```php
class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'low_stock_threshold',
        'location'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'low_stock_threshold' => 'integer'
    ];

    public function transactions(): HasMany;
    public function isLowStock(): bool;
    public function getTotalValueAttribute(): float;
}
```

#### InventoryTransaction Model
```php
class InventoryTransaction extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'type',          // purchase, usage, adjustment
        'quantity',
        'unit_price',
        'total_amount',
        'supplier',
        'purpose',
        'transaction_date',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'date'
    ];

    public function item(): BelongsTo;
    public function creator(): BelongsTo;
}
```

#### Announcement Model
```php
class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'target_type',   // all, batch, course
        'target_id',     // nullable, batch_id or course_id
        'priority',      // normal, high, urgent
        'starts_at',
        'expires_at',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function creator(): BelongsTo;
    public function batch(): BelongsTo;
    public function course(): BelongsTo;
    
    public function scopeActive($query);
    public function scopeForStudent($query, Student $student);
}
```

#### ActivityLog Model
```php
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',        // create, update, delete, login, logout, etc.
        'model_type',
        'model_id',
        'changes',       // JSON of old/new values
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function user(): BelongsTo;
    public function subject(): MorphTo;
}
```

#### ExamAttempt Model
```php
class ExamAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'started_at',
        'submitted_at',
        'answers',       // JSON of question_id => answer
        'time_per_question', // JSON tracking time spent
        'status',        // in_progress, submitted, auto_submitted
        'ip_address'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
        'time_per_question' => 'array'
    ];

    public function student(): BelongsTo;
    public function exam(): BelongsTo;
    public function result(): HasOne;
    
    public function getRemainingTimeAttribute(): int;
    public function isExpired(): bool;
}
```

#### CqSubmission Model
```php
class CqSubmission extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'files',         // JSON array of file paths
        'submitted_at',
        'evaluated_at',
        'marks',
        'feedback',
        'evaluated_by'
    ];

    protected $casts = [
        'files' => 'array',
        'submitted_at' => 'datetime',
        'evaluated_at' => 'datetime',
        'marks' => 'integer'
    ];

    public function student(): BelongsTo;
    public function exam(): BelongsTo;
    public function evaluator(): BelongsTo;
}
```

### Database Migrations

```php
// Migration: create_expenses_table
Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->string('category');
    $table->decimal('amount', 12, 2);
    $table->text('description')->nullable();
    $table->date('expense_date');
    $table->string('receipt_number')->nullable();
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
    
    $table->index(['category', 'expense_date']);
});

// Migration: create_incomes_table
Schema::create('incomes', function (Blueprint $table) {
    $table->id();
    $table->string('category');
    $table->decimal('amount', 12, 2);
    $table->text('description')->nullable();
    $table->date('income_date');
    $table->foreignId('student_id')->nullable()->constrained();
    $table->foreignId('payment_id')->nullable()->constrained();
    $table->string('reference')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
    
    $table->index(['category', 'income_date']);
});

// Migration: create_inventory_items_table
Schema::create('inventory_items', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('category');
    $table->text('description')->nullable();
    $table->integer('quantity')->default(0);
    $table->string('unit')->default('pcs');
    $table->decimal('unit_price', 10, 2)->default(0);
    $table->integer('low_stock_threshold')->default(10);
    $table->string('location')->nullable();
    $table->timestamps();
});

// Migration: create_inventory_transactions_table
Schema::create('inventory_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
    $table->enum('type', ['purchase', 'usage', 'adjustment']);
    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2)->nullable();
    $table->decimal('total_amount', 12, 2)->nullable();
    $table->string('supplier')->nullable();
    $table->string('purpose')->nullable();
    $table->date('transaction_date');
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});

// Migration: create_announcements_table
Schema::create('announcements', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->enum('target_type', ['all', 'batch', 'course']);
    $table->unsignedBigInteger('target_id')->nullable();
    $table->enum('priority', ['normal', 'high', 'urgent'])->default('normal');
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
});

// Migration: create_activity_logs_table
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained();
    $table->string('action');
    $table->string('model_type')->nullable();
    $table->unsignedBigInteger('model_id')->nullable();
    $table->json('changes')->nullable();
    $table->string('ip_address')->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();
    
    $table->index(['model_type', 'model_id']);
    $table->index(['user_id', 'created_at']);
});

// Migration: create_exam_attempts_table
Schema::create('exam_attempts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained();
    $table->foreignId('exam_id')->constrained();
    $table->timestamp('started_at');
    $table->timestamp('submitted_at')->nullable();
    $table->json('answers')->nullable();
    $table->json('time_per_question')->nullable();
    $table->enum('status', ['in_progress', 'submitted', 'auto_submitted'])->default('in_progress');
    $table->string('ip_address')->nullable();
    $table->timestamps();
    
    $table->unique(['student_id', 'exam_id']);
});

// Migration: create_cq_submissions_table
Schema::create('cq_submissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained();
    $table->foreignId('exam_id')->constrained();
    $table->json('files');
    $table->timestamp('submitted_at');
    $table->timestamp('evaluated_at')->nullable();
    $table->integer('marks')->nullable();
    $table->text('feedback')->nullable();
    $table->foreignId('evaluated_by')->nullable()->constrained('users');
    $table->timestamps();
    
    $table->unique(['student_id', 'exam_id']);
});

// Migration: add_group_links_to_batches_table
Schema::table('batches', function (Blueprint $table) {
    $table->string('telegram_link')->nullable();
    $table->string('facebook_link')->nullable();
});
```



## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

Based on the acceptance criteria analysis, the following correctness properties have been identified:

### Property 1: Student Dashboard Data Completeness
*For any* student with an enrolled batch, the dashboard service SHALL return data containing: batch information, course information, payment summary (total, paid, due), attendance percentage, and upcoming exam schedule.
**Validates: Requirements 1.1, 1.2, 1.3, 1.4, 1.5**

### Property 2: Materials Access Authorization
*For any* student and course combination, the materials service SHALL return materials only if the student is enrolled in that course's batch; otherwise, access SHALL be denied.
**Validates: Requirements 2.1, 2.5**

### Property 3: Materials Organization by Type
*For any* set of course materials, the materials service SHALL group them by type (PDF, video, document, link) with each material containing upload date and description.
**Validates: Requirements 2.2, 2.4**

### Property 4: Exam Attempt State Management
*For any* started exam attempt, the exam service SHALL correctly track: remaining time (duration minus elapsed), saved answers per question, and time spent per question.
**Validates: Requirements 3.1, 3.3, 3.7**

### Property 5: Exam Auto-Submit on Timeout
*For any* exam attempt where current time exceeds (started_at + duration_minutes), the exam service SHALL auto-submit with status 'auto_submitted' and calculate the score.
**Validates: Requirements 3.2**

### Property 6: MCQ Score Calculation
*For any* submitted MCQ exam attempt, the calculated score SHALL equal the sum of marks for questions where the student's answer matches the correct answer.
**Validates: Requirements 3.4**

### Property 7: CQ File Upload Validation
*For any* file upload to a CQ exam, the system SHALL accept only PDF and image files (jpg, jpeg, png) under the size limit, rejecting all others.
**Validates: Requirements 4.2, 4.3**

### Property 8: CQ Multiple File Storage
*For any* CQ submission, the system SHALL store all uploaded files and allow retrieval of the complete file list.
**Validates: Requirements 4.4**

### Property 9: CQ Evaluation Persistence
*For any* CQ evaluation action, the submission SHALL store marks, feedback, evaluator, and evaluation timestamp.
**Validates: Requirements 4.5**

### Property 10: Combined Result Aggregation
*For any* student with both MCQ and CQ results for the same exam, the aggregated result SHALL equal MCQ marks plus CQ marks.
**Validates: Requirements 4.6**

### Property 11: Result History Completeness
*For any* student, the results service SHALL return all past exam results with marks, grade, and rank fields populated.
**Validates: Requirements 5.1**

### Property 12: Result Filtering Accuracy
*For any* result filter by exam type or date range, the returned results SHALL only include records matching the filter criteria.
**Validates: Requirements 5.2**

### Property 13: Mark Sheet Content Completeness
*For any* generated mark sheet, the PDF SHALL contain student name, registration number, exam title, marks obtained, total marks, and calculated grade.
**Validates: Requirements 5.4**

### Property 14: Payment Summary Accuracy
*For any* student, the payment summary SHALL show total_fees equal to student.total_amount, paid_amount equal to sum of completed payments, and due_amount equal to total_fees minus paid_amount.
**Validates: Requirements 6.1, 6.4**

### Property 15: Payment Transaction Listing
*For any* student with payments, the payment list SHALL include all transactions with date, amount, and receipt_number fields.
**Validates: Requirements 6.2**

### Property 16: Expense CRUD Operations
*For any* valid expense data with amount, category (from allowed list), and date, create/update/delete operations SHALL succeed and persist all fields correctly.
**Validates: Requirements 7.1, 7.2, 7.3, 7.4**

### Property 17: Expense Filtering and Pagination
*For any* expense filter by category and/or date range, the returned expenses SHALL only include matching records in paginated format.
**Validates: Requirements 7.5**

### Property 18: Automatic Income from Payments
*For any* completed payment, an income record SHALL be automatically created with category 'tuition', amount matching payment amount, and payment_id reference.
**Validates: Requirements 8.1**

### Property 19: Income Record Completeness
*For any* income record, the display SHALL include category, amount, date, and student reference if student_id is set.
**Validates: Requirements 8.3, 8.4**

### Property 20: Daily Financial Summary
*For any* date, the daily summary SHALL return total_income equal to sum of incomes for that date, and total_expenses equal to sum of expenses for that date.
**Validates: Requirements 9.1**

### Property 21: Monthly Financial Report
*For any* year and month, the monthly report SHALL return income and expenses grouped by category with correct totals.
**Validates: Requirements 9.2**

### Property 22: Profit/Loss Calculation
*For any* date range, profit/loss SHALL equal total income minus total expenses for that period.
**Validates: Requirements 9.5**

### Property 23: Inventory Stock Tracking
*For any* inventory item, the current quantity SHALL equal initial quantity plus sum of purchase quantities minus sum of usage quantities.
**Validates: Requirements 10.1, 10.2**

### Property 24: Low Stock Alert Accuracy
*For any* inventory item where quantity is less than low_stock_threshold, the item SHALL appear in the low stock alerts list.
**Validates: Requirements 10.3**

### Property 25: Inventory Transaction Recording
*For any* purchase or usage transaction, all fields (date, quantity, supplier/purpose, cost) SHALL be persisted and retrievable.
**Validates: Requirements 10.4, 10.5**

### Property 26: Inventory Report Value Calculation
*For any* inventory report, total stock value SHALL equal sum of (quantity × unit_price) for all items.
**Validates: Requirements 10.6**

### Property 27: Email Notification Triggers
*For any* completed payment, published result, or low attendance event, an email notification SHALL be queued with appropriate template and recipient.
**Validates: Requirements 11.1, 11.2, 11.3**

### Property 28: Email Template Rendering
*For any* email template with placeholders like {student_name}, {amount}, rendering with actual values SHALL replace all placeholders with corresponding data.
**Validates: Requirements 11.4**

### Property 29: Email Logging
*For any* sent email, a log record SHALL exist with recipient, subject, status, and timestamp.
**Validates: Requirements 11.5**

### Property 30: Batch Group Links Display
*For any* student in a batch with telegram_link or facebook_link set, the student portal SHALL display those links.
**Validates: Requirements 12.1, 12.3**

### Property 31: Announcement Targeting
*For any* announcement with target_type 'batch' and target_id, only students in that batch SHALL see the announcement; for 'all', all students SHALL see it.
**Validates: Requirements 13.1, 13.2**

### Property 32: Announcement Expiry
*For any* announcement with expires_at in the past, the announcement SHALL NOT appear in active announcements list.
**Validates: Requirements 13.3**

### Property 33: Material Upload Validation
*For any* material upload, the system SHALL accept only allowed file types (PDF, video, image) under size limit, rejecting invalid files.
**Validates: Requirements 14.1, 14.2, 14.4**

### Property 34: Material Reordering
*For any* material reorder operation, the order values SHALL update correctly and materials SHALL be retrievable in the new order.
**Validates: Requirements 14.3**

### Property 35: Schedule Conflict Prevention
*For any* new class schedule, if another schedule exists with the same room, day, and overlapping time, creation SHALL fail with conflict error.
**Validates: Requirements 15.3**

### Property 36: Weekly Timetable Organization
*For any* batch, the schedule service SHALL return schedules grouped by day_of_week in chronological order by start_time.
**Validates: Requirements 15.2**

### Property 37: Exam Routine Filtering
*For any* exam routine filter by batch and/or date range, returned exams SHALL only include matching records with name, date, time, and duration.
**Validates: Requirements 16.1, 16.2**

### Property 38: Salary Duplicate Prevention
*For any* salary payment where a record already exists for the same teacher_id and month, creation SHALL fail with duplicate error.
**Validates: Requirements 17.5**

### Property 39: Salary Report Aggregation
*For any* month, the salary report SHALL return total paid equal to sum of salary amounts for that month.
**Validates: Requirements 17.3**

### Property 40: Activity Log Creation
*For any* create, update, or delete operation on tracked models, an activity log entry SHALL be created with user_id, action, model_type, model_id, and changes.
**Validates: Requirements 19.1, 19.2**

### Property 41: Activity Log Filtering
*For any* log filter by user, action type, or date range, returned logs SHALL only include matching records.
**Validates: Requirements 19.3**

### Property 42: Import Data Validation
*For any* CSV/Excel import, the system SHALL parse the file, validate each row against required fields, and return errors for invalid rows before committing.
**Validates: Requirements 20.1, 20.2**

### Property 43: Import Preview Accuracy
*For any* parsed import file, the preview SHALL show correct row count and list of detected column headers.
**Validates: Requirements 20.3**

### Property 44: Import Column Mapping
*For any* import with column mapping, the imported records SHALL use values from mapped columns for corresponding database fields.
**Validates: Requirements 20.4**

## Error Handling

### Student Portal Errors
| Error Condition | Response | HTTP Code |
|----------------|----------|-----------|
| Student not authenticated | Redirect to login | 302 |
| Student not enrolled in course | Access denied message | 403 |
| Material file not found | File not found error | 404 |
| Exam not available | Exam not accessible message | 403 |
| Exam already submitted | Already submitted error | 400 |
| Exam time expired | Auto-submit and show results | 200 |

### Exam Engine Errors
| Error Condition | Response | HTTP Code |
|----------------|----------|-----------|
| Invalid exam ID | Exam not found | 404 |
| Exam not started yet | Exam not available | 403 |
| Exam already ended | Exam closed | 403 |
| Invalid file type for CQ | Validation error | 422 |
| File size exceeded | Validation error | 422 |
| Duplicate submission | Already submitted | 400 |

### Accounts Module Errors
| Error Condition | Response | HTTP Code |
|----------------|----------|-----------|
| Missing required fields | Validation error | 422 |
| Invalid category | Validation error | 422 |
| Invalid date format | Validation error | 422 |
| Record not found | Not found | 404 |
| Unauthorized access | Forbidden | 403 |

### Inventory Errors
| Error Condition | Response | HTTP Code |
|----------------|----------|-----------|
| Insufficient stock for usage | Insufficient stock error | 400 |
| Negative quantity | Validation error | 422 |
| Item not found | Not found | 404 |
| Duplicate item name | Validation error | 422 |

### Import Errors
| Error Condition | Response | HTTP Code |
|----------------|----------|-----------|
| Invalid file format | Unsupported format | 422 |
| File too large | File size exceeded | 422 |
| Missing required columns | Validation error with details | 422 |
| Invalid data in rows | Validation errors per row | 422 |
| Database constraint violation | Import failed with details | 500 |

### Backup Errors
| Error Condition | Response | HTTP Code |
|----------------|----------|-----------|
| Backup creation failed | Server error | 500 |
| Backup file not found | Not found | 404 |
| Restore failed | Restore error with details | 500 |
| Insufficient permissions | Forbidden | 403 |

## Testing Strategy

### Unit Testing Approach
Unit tests will focus on:
- Service method logic validation
- Model attribute casting and accessors
- Validation rule enforcement
- Edge cases and error conditions

### Integration Testing Approach
Integration tests will cover:
- Controller-Service-Model flow
- Database transactions
- File upload/download operations
- Email sending (using fake mailer)

### Key Test Areas

#### Student Portal Tests
- Dashboard data retrieval for various student states
- Materials access authorization
- Exam attempt lifecycle (start, save, submit)
- Result and payment history retrieval

#### Exam Engine Tests
- Timer calculation accuracy
- Auto-submit trigger conditions
- MCQ score calculation
- CQ file upload validation
- Combined result aggregation

#### Accounts Module Tests
- Expense CRUD operations
- Income auto-creation from payments
- Financial report calculations
- Export file generation

#### Inventory Tests
- Stock level calculations after transactions
- Low stock alert triggering
- Transaction history accuracy

#### Communication Tests
- Email template rendering
- Notification trigger conditions
- Announcement targeting logic

#### Admin Enhancement Tests
- Schedule conflict detection
- Salary duplicate prevention
- Activity log creation
- Import validation and mapping

### Test Data Strategy
- Use Laravel factories for generating test data
- Create seeders for common test scenarios
- Use database transactions for test isolation

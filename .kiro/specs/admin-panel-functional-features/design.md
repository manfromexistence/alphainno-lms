# Design Document: Admin Panel Functional Features

## Overview

This design document outlines the implementation approach for making the payment, report, and communication features fully functional in the Laravel LMS admin panel. The implementation will leverage existing Laravel patterns, services, and models while adding new functionality for payment processing, report generation with exports, and SMS communication with logging.

The system will be built on top of the existing Laravel application structure, utilizing:
- Existing models (Student, Payment, SmsLog, etc.)
- Existing services (PaymentService, ReportService, SmsService)
- Existing admin panel UI structure
- Laravel Excel package for Excel exports
- DomPDF or similar for PDF generation
- Database-backed SMS logging with mock implementation

## Architecture

### High-Level Architecture

The system follows Laravel's MVC architecture with a service layer:

```
┌─────────────────────────────────────────────────────────┐
│                     Admin Panel UI                       │
│              (Blade Templates + JavaScript)              │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                      Controllers                         │
│  PaymentController │ ReportController │ CommunicationController │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                     Service Layer                        │
│  PaymentService │ ReportService │ SmsService │ ExportService │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                    Models & Database                     │
│  Student │ Payment │ Invoice │ SmsLog │ SmsTemplate │ etc. │
└─────────────────────────────────────────────────────────┘
```

### Module Breakdown

**Payment Module:**
- Controllers: `Admin\PaymentController`
- Services: `PaymentService`, `InvoiceService`
- Models: `Payment`, `Invoice`, `Student`
- Views: Payment forms, invoice templates, receipt templates

**Report Module:**
- Controllers: `Admin\ReportController`
- Services: `ReportService`, `ExportService`
- Exports: Laravel Excel export classes for each report type
- Views: Report display pages, PDF templates

**Communication Module:**
- Controllers: `Admin\CommunicationController`
- Services: `SmsService`, `SmsTemplateService`
- Models: `SmsLog`, `SmsTemplate`
- Views: SMS sending forms, template management, SMS logs

## Components and Interfaces

### Payment Module Components

#### PaymentController

Handles HTTP requests for payment operations:

```php
class PaymentController extends Controller
{
    public function index(Request $request): View
    // Display payment dashboard with filters
    
    public function create(Student $student): View
    // Show payment form for a student
    
    public function store(PaymentRequest $request): RedirectResponse
    // Process and record a new payment
    
    public function show(Payment $payment): View
    // Display payment details
    
    public function history(Student $student): View
    // Show complete payment history for a student
    
    public function receipt(Payment $payment): View
    // Generate printable receipt
    
    public function dashboard(): View
    // Display payment statistics and due amounts
}
```

#### PaymentService

Business logic for payment processing:

```php
class PaymentService
{
    public function recordPayment(array $data): Payment
    // Create payment record and update student balance
    
    public function calculateBalance(Student $student): float
    // Calculate current balance for a student
    
    public function getPaymentHistory(Student $student, array $filters = []): Collection
    // Retrieve filtered payment history
    
    public function getDashboardStats(array $filters = []): array
    // Calculate dashboard statistics (total due, revenue, etc.)
    
    public function getPaymentsByMethod(string $method, array $filters = []): Collection
    // Get payments filtered by payment method
}
```

#### InvoiceService

Invoice generation and management:

```php
class InvoiceService
{
    public function generateInvoiceNumber(): string
    // Generate unique sequential invoice number
    
    public function createInvoice(Student $student, float $amount, array $details): Invoice
    // Create new invoice record
    
    public function getInvoice(string $invoiceNumber): ?Invoice
    // Retrieve invoice by number
    
    public function markInvoiceAsPaid(Invoice $invoice, Payment $payment): void
    // Update invoice status when payment is recorded
}
```

### Report Module Components

#### ReportController

Handles report generation and export requests:

```php
class ReportController extends Controller
{
    public function attendance(Request $request): View
    // Display attendance report with filters
    
    public function exportAttendanceExcel(Request $request): BinaryFileResponse
    // Export attendance report to Excel
    
    public function exportAttendancePdf(Request $request): Response
    // Export attendance report to PDF
    
    public function payment(Request $request): View
    // Display payment summary report
    
    public function exportPaymentExcel(Request $request): BinaryFileResponse
    // Export payment report to Excel
    
    public function exportPaymentPdf(Request $request): Response
    // Export payment report to PDF
    
    public function performance(Request $request): View
    // Display performance/exam report
    
    public function exportPerformanceExcel(Request $request): BinaryFileResponse
    // Export performance report to Excel
    
    public function exportPerformancePdf(Request $request): Response
    // Export performance report to PDF
    
    public function student(Request $request): View
    // Display comprehensive student report
    
    public function exportStudentExcel(Request $request): BinaryFileResponse
    // Export student report to Excel
    
    public function exportStudentPdf(Request $request): Response
    // Export student report to PDF
    
    public function dashboardData(Request $request): JsonResponse
    // Return data for dashboard charts and visualizations
}
```

#### ReportService

Data retrieval and processing for reports:

```php
class ReportService
{
    public function getAttendanceReport(array $filters): Collection
    // Retrieve attendance data with filters (batch, date range, student)
    
    public function calculateAttendanceStats(Collection $attendance): array
    // Calculate attendance percentages and statistics
    
    public function getPaymentReport(array $filters): Collection
    // Retrieve payment data with filters (date, batch, method)
    
    public function calculatePaymentStats(Collection $payments): array
    // Calculate revenue, method breakdown, outstanding dues
    
    public function getPerformanceReport(array $filters): Collection
    // Retrieve exam and grade data with filters (batch, course, exam)
    
    public function calculatePerformanceStats(Collection $results): array
    // Calculate averages, pass rates, grade distributions
    
    public function getStudentReport(array $filters): Collection
    // Compile comprehensive student data (enrollment, payments, performance)
    
    public function getDashboardChartData(string $chartType, array $filters): array
    // Generate data for specific dashboard charts
}
```

#### ExportService

Handles Excel and PDF export generation:

```php
class ExportService
{
    public function exportToExcel(string $reportType, Collection $data, array $filters): BinaryFileResponse
    // Generate Excel file using Laravel Excel
    
    public function exportToPdf(string $reportType, Collection $data, array $filters): Response
    // Generate PDF file using DomPDF
    
    public function getExportClass(string $reportType): string
    // Return appropriate Laravel Excel export class
    
    public function getPdfView(string $reportType): string
    // Return appropriate Blade view for PDF generation
}
```

#### Laravel Excel Export Classes

```php
class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    // Return attendance data
    
    public function headings(): array
    // Define Excel column headers
    
    public function map($row): array
    // Map data to Excel columns
}

class PaymentExport implements FromCollection, WithHeadings, WithMapping
{
    // Similar structure for payment data
}

class PerformanceExport implements FromCollection, WithHeadings, WithMapping
{
    // Similar structure for performance data
}

class StudentExport implements FromCollection, WithHeadings, WithMapping
{
    // Similar structure for student data
}
```

### Communication Module Components

#### CommunicationController

Handles SMS sending and template management:

```php
class CommunicationController extends Controller
{
    public function index(): View
    // Display SMS dashboard and sending form
    
    public function send(SendSmsRequest $request): RedirectResponse
    // Send SMS to selected recipients
    
    public function bulkSend(BulkSmsRequest $request): JsonResponse
    // Send bulk SMS with progress tracking
    
    public function logs(Request $request): View
    // Display SMS logs with filtering
    
    public function retry(SmsLog $smsLog): RedirectResponse
    // Retry failed SMS
    
    public function retryBulk(Request $request): JsonResponse
    // Retry multiple failed SMS
    
    public function templates(): View
    // Display SMS template management page
    
    public function storeTemplate(TemplateRequest $request): RedirectResponse
    // Create new SMS template
    
    public function updateTemplate(SmsTemplate $template, TemplateRequest $request): RedirectResponse
    // Update existing template
    
    public function deleteTemplate(SmsTemplate $template): RedirectResponse
    // Delete SMS template
    
    public function sendPaymentNotification(Payment $payment): RedirectResponse
    // Send payment confirmation SMS
    
    public function sendPaymentReminders(Request $request): JsonResponse
    // Send payment reminder SMS to students with dues
    
    public function sendResultNotification(Request $request): JsonResponse
    // Send exam result notifications
}
```

#### SmsService

SMS sending and logging logic:

```php
class SmsService
{
    public function send(string $phone, string $message, array $metadata = []): SmsLog
    // Send SMS and create log entry (mock implementation)
    
    public function sendBulk(array $recipients, string $message, array $metadata = []): array
    // Send SMS to multiple recipients, return summary
    
    public function logSms(string $phone, string $message, string $status, array $metadata = []): SmsLog
    // Create SMS log entry
    
    public function updateStatus(SmsLog $smsLog, string $status): void
    // Update SMS delivery status
    
    public function retry(SmsLog $smsLog): SmsLog
    // Retry failed SMS
    
    public function getLogs(array $filters = []): Collection
    // Retrieve SMS logs with filtering
    
    public function mockSend(string $phone, string $message): array
    // Mock SMS sending implementation (simulates success/failure)
}
```

#### SmsTemplateService

Template management and placeholder replacement:

```php
class SmsTemplateService
{
    public function create(array $data): SmsTemplate
    // Create new template
    
    public function update(SmsTemplate $template, array $data): SmsTemplate
    // Update existing template
    
    public function delete(SmsTemplate $template): bool
    // Delete template
    
    public function getAll(): Collection
    // Retrieve all templates
    
    public function replacePlaceholders(string $template, array $data): string
    // Replace placeholders with actual values
    
    public function getAvailablePlaceholders(): array
    // Return list of available placeholders
}
```

## Data Models

### Payment Model

```php
class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'invoice_id',
        'amount',
        'payment_method',
        'payment_date',
        'transaction_reference',
        'notes',
        'recorded_by'
    ];
    
    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];
    
    public function student(): BelongsTo
    public function invoice(): BelongsTo
    public function recordedBy(): BelongsTo
}
```

### Invoice Model

```php
class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'student_id',
        'amount',
        'due_date',
        'status',
        'description',
        'created_by'
    ];
    
    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2'
    ];
    
    public function student(): BelongsTo
    public function payments(): HasMany
    public function isPaid(): bool
    public function getRemainingAmount(): float
}
```

### SmsLog Model

```php
class SmsLog extends Model
{
    protected $fillable = [
        'recipient_phone',
        'recipient_name',
        'recipient_type',
        'message',
        'status',
        'sent_at',
        'delivered_at',
        'failed_at',
        'error_message',
        'metadata',
        'sent_by'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
        'metadata' => 'array'
    ];
    
    public function sentBy(): BelongsTo
    public function isFailed(): bool
    public function canRetry(): bool
}
```

### SmsTemplate Model

```php
class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'content',
        'placeholders',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'placeholders' => 'array',
        'is_active' => 'boolean'
    ];
    
    public function render(array $data): string
    // Replace placeholders and return final message
}
```

## Correctness Properties


*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

**Note:** Since the user has explicitly requested NO TESTS for this implementation, the properties below serve as design documentation and validation criteria rather than automated test specifications. They describe what the system should do correctly, but will not be implemented as property-based tests.

### Payment Module Properties

**Property 1: Mobile Money Display Conditional**
*For any* payment form where the selected payment method is bKash or Nagad, the form should display the institution's phone number and payment instructions.
**Validates: Requirements 1.2**

**Property 2: Invoice Number Uniqueness**
*For any* two payments recorded in the system, their invoice numbers should be distinct.
**Validates: Requirements 1.3, 2.1**

**Property 3: Payment Data Persistence**
*For any* payment recorded, retrieving that payment from the database should return all stored fields (amount, method, date, student information) with their original values.
**Validates: Requirements 1.4**

**Property 4: Balance Update Consistency**
*For any* student and payment amount, recording a payment should decrease the student's balance by exactly that payment amount.
**Validates: Requirements 1.5**

**Property 5: Invoice Document Completeness**
*For any* generated invoice, the document should contain student details, amount, due date, and payment instructions.
**Validates: Requirements 2.2**

**Property 6: Receipt Generation on Payment**
*For any* completed payment, a receipt should be generated containing payment details and the associated invoice reference.
**Validates: Requirements 2.3**

**Property 7: Invoice and Receipt History Persistence**
*For any* invoice or receipt created, it should be retrievable from the system's history at any later time.
**Validates: Requirements 2.5**

**Property 8: Dashboard Due Amount Calculation**
*For any* set of students with fees and payments, the dashboard's total due amount should equal the sum of all individual student balances.
**Validates: Requirements 3.1**

**Property 9: Payment History Completeness**
*For any* student, searching for that student should return all their payment records with complete information (dates, amounts, methods).
**Validates: Requirements 3.2**

**Property 10: Payment Filter Accuracy**
*For any* filter criteria (date range, payment method, batch, or student), the returned payments should only include records matching all specified criteria.
**Validates: Requirements 3.3**

**Property 11: Student Balance Calculation**
*For any* student, their current balance should equal their total fees minus their total payments.
**Validates: Requirements 3.4**

### Communication Module Properties

**Property 12: Payment Confirmation SMS Trigger**
*For any* payment recorded, an SMS should be sent to the student and a corresponding log entry should be created.
**Validates: Requirements 4.1, 4.4**

**Property 13: Payment Reminder Targeting**
*For any* student with a positive balance (outstanding dues), triggering payment reminders should result in an SMS being sent to that student.
**Validates: Requirements 4.2**

**Property 14: Payment Notification Content**
*For any* payment notification SMS, the message should contain the payment amount, payment date, and current balance.
**Validates: Requirements 4.3**

**Property 15: SMS Logging Completeness**
*For any* SMS sent or attempted, a log entry should be created containing recipient phone, message content, timestamp, and delivery status.
**Validates: Requirements 10.1, 10.2**

**Property 16: SMS Log Filtering**
*For any* filter criteria applied to SMS logs, only log entries matching the criteria should be returned.
**Validates: Requirements 10.3**

**Property 17: SMS Status Update Persistence**
*For any* SMS log with a status change, retrieving that log should reflect the updated status.
**Validates: Requirements 10.4**

**Property 18: Bulk SMS Recipient Selection**
*For any* recipient selection criteria (batch, course, or custom list), the identified recipients should match the criteria exactly.
**Validates: Requirements 11.1**

**Property 19: Bulk SMS Individual Logging**
*For any* bulk SMS operation with N recipients, exactly N individual SMS log entries should be created.
**Validates: Requirements 11.2**

**Property 20: Bulk SMS Summary Accuracy**
*For any* completed bulk SMS operation, the summary counts (successful + failed) should equal the total number of recipients.
**Validates: Requirements 11.4**

**Property 21: Recipient Type Support**
*For any* bulk SMS operation, both student and parent recipient types should be supported and processed correctly.
**Validates: Requirements 11.5**

**Property 22: Template Data Persistence**
*For any* SMS template created, retrieving that template should return the stored name and message content.
**Validates: Requirements 12.1**

**Property 23: Placeholder Replacement**
*For any* SMS template containing placeholders and recipient data, sending the SMS should replace all placeholders with the corresponding recipient-specific values.
**Validates: Requirements 12.2, 12.5**

**Property 24: Template Update Persistence**
*For any* template edit, retrieving the template after the edit should reflect the updated content.
**Validates: Requirements 12.3**

**Property 25: Template Deletion**
*For any* template deleted, attempting to retrieve that template should return null or not found.
**Validates: Requirements 12.4**

**Property 26: Result Notification Content**
*For any* result notification SMS, the message should include exam name, score, and grade information.
**Validates: Requirements 13.2**

**Property 27: Result Notification Coverage**
*For any* exam with N students, triggering result notifications should send SMS to all N students.
**Validates: Requirements 13.3**

**Property 28: Result Notification Logging**
*For any* result notification sent, the SMS log should include exam details in the metadata.
**Validates: Requirements 13.4**

**Property 29: SMS Status Validity**
*For any* SMS log entry, the status should be one of: pending, sent, failed, or delivered.
**Validates: Requirements 14.1**

**Property 30: Failed SMS Marking**
*For any* SMS that fails to send, the log entry should be marked with status "failed".
**Validates: Requirements 14.3**

**Property 31: Retry Log Creation**
*For any* failed SMS that is retried, a new log entry should be created for the retry attempt.
**Validates: Requirements 14.5**

**Property 32: Mock SMS Delivery Simulation**
*For any* SMS sent using the mock implementation, it should eventually be marked as delivered (simulating successful delivery).
**Validates: Requirements 15.2**

**Property 33: Mock SMS Log Structure**
*For any* mock SMS log entry, it should contain the same fields as a real SMS log entry (phone, message, status, timestamps, metadata).
**Validates: Requirements 15.4**

### Report Module Properties

**Property 34: Attendance Report Filtering**
*For any* filter criteria (batch, date range, or student), the attendance report should only include records matching all specified criteria.
**Validates: Requirements 5.2**

**Property 35: Attendance Excel Export Generation**
*For any* attendance data set, exporting to Excel should generate a valid Excel file with the data.
**Validates: Requirements 5.3**

**Property 36: Attendance PDF Export Generation**
*For any* attendance data set, exporting to PDF should generate a valid PDF file with the data.
**Validates: Requirements 5.4**

**Property 37: Attendance Statistics Calculation**
*For any* attendance data set, the calculated attendance percentage should equal (present days / total days) × 100.
**Validates: Requirements 5.5**

**Property 38: Payment Report Filtering**
*For any* filter criteria (date range, batch, or payment method), the payment report should only include records matching all specified criteria.
**Validates: Requirements 6.2**

**Property 39: Payment Excel Export Generation**
*For any* payment data set, exporting to Excel should generate a valid Excel file with the data.
**Validates: Requirements 6.3**

**Property 40: Payment PDF Export Generation**
*For any* payment data set, exporting to PDF should generate a valid PDF file with the data.
**Validates: Requirements 6.4**

**Property 41: Payment Report Statistics**
*For any* payment data set, the report should correctly calculate total revenue (sum of all payments), payment method breakdown (sum per method), and outstanding dues (sum of all balances).
**Validates: Requirements 6.5**

**Property 42: Performance Report Filtering**
*For any* filter criteria (batch, course, or exam), the performance report should only include results matching all specified criteria.
**Validates: Requirements 7.2**

**Property 43: Performance Excel Export Generation**
*For any* performance data set, exporting to Excel should generate a valid Excel file with scores and rankings.
**Validates: Requirements 7.3**

**Property 44: Performance PDF Export Generation**
*For any* performance data set, exporting to PDF should generate a valid PDF file with scores and rankings.
**Validates: Requirements 7.4**

**Property 45: Performance Statistics Calculation**
*For any* performance data set, the report should correctly calculate averages (sum of scores / count), pass rates (passed students / total students), and grade distributions (count per grade).
**Validates: Requirements 7.5**

**Property 46: Student Report Data Compilation**
*For any* student, the comprehensive report should include their enrollment data, payment history, and performance results.
**Validates: Requirements 8.1**

**Property 47: Student Report Completeness**
*For any* student report, it should include enrollment date, batch, courses, payment history, and exam results.
**Validates: Requirements 8.2**

**Property 48: Student Excel Export Generation**
*For any* student data set, exporting to Excel should generate a valid Excel file with comprehensive student information.
**Validates: Requirements 8.3**

**Property 49: Student PDF Export Generation**
*For any* student data set, exporting to PDF should generate a valid PDF file with comprehensive student information.
**Validates: Requirements 8.4**

**Property 50: Student Search and Filter**
*For any* search criteria (name, batch, or enrollment status), only students matching the criteria should be returned.
**Validates: Requirements 8.5**

**Property 51: Dashboard Chart Data Generation**
*For any* chart type (payment trends, attendance statistics, enrollment distribution, performance distribution), the chart data should be generated from the corresponding database records.
**Validates: Requirements 9.1, 9.2, 9.3, 9.4**

**Property 52: Dashboard Filter Application**
*For any* dashboard chart with applied filters (date range, batch, etc.), the chart data should reflect only the filtered dataset.
**Validates: Requirements 9.5**

**Property 53: Excel Column Headers**
*For any* Excel export, the file should include proper column headers matching the data fields.
**Validates: Requirements 16.2**

**Property 54: Excel Filter Consistency**
*For any* report view with applied filters, the Excel export should contain exactly the same filtered data.
**Validates: Requirements 16.3**

**Property 55: Excel File Generation**
*For any* Excel export request, a valid Excel file should be generated and made available for download.
**Validates: Requirements 16.4**

**Property 56: PDF Branding and Formatting**
*For any* PDF export, the file should include institution branding and proper formatting.
**Validates: Requirements 17.2**

**Property 57: PDF Filter Consistency**
*For any* report view with applied filters, the PDF export should contain exactly the same filtered data.
**Validates: Requirements 17.3**

**Property 58: PDF File Generation**
*For any* PDF export request, a valid PDF file should be generated and made available for download.
**Validates: Requirements 17.4**

## Error Handling

### Payment Module Error Handling

**Invalid Payment Data:**
- Validate all payment fields before processing
- Return validation errors with specific field messages
- Prevent payment recording if student doesn't exist
- Prevent negative payment amounts
- Require payment method selection

**Invoice Generation Failures:**
- Handle invoice number collision (retry with next number)
- Validate student exists before creating invoice
- Ensure due date is not in the past
- Handle database transaction failures with rollback

**Balance Calculation Errors:**
- Handle missing payment records gracefully
- Prevent negative balances from being displayed incorrectly
- Handle concurrent payment updates with database locking

### Report Module Error Handling

**Data Retrieval Failures:**
- Handle empty result sets gracefully (show "No data available")
- Catch database query errors and display user-friendly messages
- Handle invalid filter parameters with validation errors

**Export Generation Failures:**
- Catch Excel generation errors and display error messages
- Catch PDF generation errors and display error messages
- Handle large datasets that might cause memory issues (pagination or chunking)
- Validate export data before generation

**Chart Generation Failures:**
- Handle empty datasets for charts (show "No data" message)
- Catch chart library errors and display fallback messages
- Validate chart data format before rendering

### Communication Module Error Handling

**SMS Sending Failures:**
- Log all failed SMS attempts with error messages
- Mark SMS as failed in database
- Provide retry mechanism for failed SMS
- Handle invalid phone numbers with validation
- Handle empty message content with validation

**Template Management Errors:**
- Validate template content before saving
- Prevent deletion of templates currently in use
- Handle placeholder syntax errors gracefully
- Validate placeholder data before replacement

**Bulk SMS Errors:**
- Continue processing remaining recipients if one fails
- Track individual failures in bulk operations
- Provide detailed error summary after bulk operation
- Handle timeout for large bulk operations

## Testing Strategy

**Note:** The user has explicitly requested NO TESTS for this implementation. The following testing strategy is provided for documentation purposes only and will NOT be implemented.

### Manual Testing Approach

Since automated tests are not being created, the following manual testing approach should be used:

**Payment Module Manual Testing:**
- Manually test each payment method (Cash, bKash, Nagad, Bank Transfer)
- Verify invoice numbers are sequential and unique
- Check payment receipts are generated correctly
- Verify student balances update after payments
- Test payment filtering and search functionality
- Verify payment dashboard statistics are accurate

**Report Module Manual Testing:**
- Test each report type with various filters
- Verify Excel exports download and open correctly
- Verify PDF exports download and display correctly
- Check report calculations (percentages, totals, averages)
- Test dashboard charts with different date ranges
- Verify empty state handling for reports

**Communication Module Manual Testing:**
- Test SMS sending to individual recipients
- Test bulk SMS with different recipient selections
- Verify SMS logs are created for all attempts
- Test SMS template creation, editing, and deletion
- Verify placeholder replacement in templates
- Test SMS retry functionality for failed messages
- Verify payment and result notifications are sent correctly

### Integration Testing

- Test payment recording triggers SMS notification
- Test report exports use correct filtered data
- Test dashboard updates after payment changes
- Test bulk SMS with actual student/parent data
- Test end-to-end payment flow (invoice → payment → receipt → SMS)

### User Acceptance Testing

- Have admin users test all payment workflows
- Have admin users generate and verify all report types
- Have admin users test SMS sending and template management
- Gather feedback on UI/UX and make adjustments
- Verify all features work with real production data


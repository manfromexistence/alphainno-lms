# Implementation Plan: LMS Completion

## Overview

This implementation plan covers all missing features in the Laravel LMS project, organized into logical phases. Tasks are ordered to build incrementally, with the Student Portal (highest priority) implemented first. Each task references specific requirements for traceability.

## Tasks

- [ ] 1. Database Migrations and Models
  - [x] 1.1 Create Expense model and migration
    - Create migration with: category, amount, description, expense_date, receipt_number, notes, created_by
    - Create Expense model with fillable, casts, relationships, and CATEGORIES constant
    - _Requirements: 7.1, 7.2_
  
  - [x] 1.2 Create Income model and migration
    - Create migration with: category, amount, description, income_date, student_id, payment_id, reference, created_by
    - Create Income model with fillable, casts, and relationships to Student, Payment, User
    - _Requirements: 8.1, 8.2, 8.3_
  
  - [x] 1.3 Create InventoryItem model and migration
    - Create migration with: name, category, description, quantity, unit, unit_price, low_stock_threshold, location
    - Create InventoryItem model with isLowStock() method and getTotalValueAttribute()
    - _Requirements: 10.1, 10.2, 10.3_
  
  - [x] 1.4 Create InventoryTransaction model and migration
    - Create migration with: inventory_item_id, type (enum), quantity, unit_price, total_amount, supplier, purpose, transaction_date, notes, created_by
    - Create InventoryTransaction model with relationships
    - _Requirements: 10.4, 10.5_
  
  - [ ] 1.5 Create Announcement model and migration
    - Create migration with: title, content, target_type, target_id, priority, starts_at, expires_at, is_active, created_by
    - Create Announcement model with scopeActive(), scopeForStudent() methods
    - _Requirements: 13.1, 13.2, 13.3_
  
  - [~] 1.6 Create ActivityLog model and migration
    - Create migration with: user_id, action, model_type, model_id, changes (json), ip_address, user_agent
    - Create ActivityLog model with relationships and indexes
    - _Requirements: 19.1, 19.2_
  
  - [~] 1.7 Create ExamAttempt model and migration
    - Create migration with: student_id, exam_id, started_at, submitted_at, answers (json), time_per_question (json), status, ip_address
    - Create ExamAttempt model with getRemainingTimeAttribute() and isExpired() methods
    - Add unique constraint on student_id + exam_id
    - _Requirements: 3.1, 3.3, 3.7_
  
  - [~] 1.8 Create CqSubmission model and migration
    - Create migration with: student_id, exam_id, files (json), submitted_at, evaluated_at, marks, feedback, evaluated_by
    - Create CqSubmission model with relationships
    - Add unique constraint on student_id + exam_id
    - _Requirements: 4.2, 4.4, 4.5_
  
  - [~] 1.9 Add group links to batches table
    - Create migration to add telegram_link and facebook_link columns to batches table
    - Update Batch model fillable array
    - _Requirements: 12.1, 12.2_

- [ ] 2. Checkpoint - Run migrations and verify models
  - Run `php artisan migrate`
  - Verify all models are correctly created
  - Ensure all tests pass, ask the user if questions arise

- [ ] 3. Student Portal - Core Services
  - [~] 3.1 Create StudentPortalService
    - Implement getDashboardData(Student $student) returning batch, course, payment summary, attendance, upcoming exams
    - Implement getPaymentSummary(Student $student) returning total, paid, due amounts
    - Implement getAttendancePercentage(Student $student, ?Carbon $month) 
    - Implement getUpcomingExams(Student $student) returning scheduled exams for student's batch
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_
  
  - [~] 3.2 Create ExamTakingService
    - Implement startAttempt(Student $student, Exam $exam) creating ExamAttempt record
    - Implement saveAnswer(ExamAttempt $attempt, int $questionId, string $answer) for auto-save
    - Implement submitExam(ExamAttempt $attempt) calculating score and creating ExamResult
    - Implement calculateMcqScore(ExamAttempt $attempt) comparing answers to correct answers
    - Implement getExamWithTimer(ExamAttempt $attempt) returning exam data with remaining time
    - Implement autoSubmitExpired() for scheduled job to auto-submit timed-out exams
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.7_
  
  - [~] 3.3 Extend ExamTakingService for CQ exams
    - Implement submitCqAnswer(Student $student, Exam $exam, array $files) handling file uploads
    - Implement validateCqFiles(array $files) checking type (pdf, jpg, png) and size
    - Implement evaluateCq(CqSubmission $submission, int $marks, string $feedback)
    - Implement getAggregatedResult(Student $student, Exam $exam) combining MCQ and CQ marks
    - _Requirements: 4.2, 4.3, 4.4, 4.5, 4.6_
  
  - [~] 3.4 Create MarkSheetService
    - Implement generateMarkSheet(Student $student, ?Exam $exam) returning PDF content
    - Use DomPDF to render mark sheet with student details, exam details, marks, grade
    - Create mark sheet Blade template at resources/views/pdf/mark-sheet.blade.php
    - _Requirements: 5.3, 5.4_

- [ ] 4. Student Portal - Controller and Routes
  - [~] 4.1 Implement StudentPortalController
    - Implement dashboard() method using StudentPortalService
    - Implement materials() method returning course materials for enrolled student
    - Implement downloadMaterial(CourseMaterial $material) with authorization check
    - Implement schedule() method returning student's class schedule
    - Implement payments() method returning payment history and summary
    - Implement downloadReceipt(Payment $payment) generating receipt PDF
    - _Requirements: 1.1-1.5, 2.1-2.5, 6.1-6.4, 15.5_
  
  - [~] 4.2 Implement exam methods in StudentPortalController
    - Implement exams() method listing available and past exams
    - Implement startExam(Exam $exam) initializing exam attempt with timer
    - Implement saveAnswer(Request $request, ExamAttempt $attempt) for AJAX auto-save
    - Implement submitExam(Request $request, Exam $exam) processing submission
    - Implement examResult(ExamResult $result) showing result with explanations
    - _Requirements: 3.1-3.7_
  
  - [~] 4.3 Implement CQ exam methods in StudentPortalController
    - Implement showCqExam(Exam $exam) displaying question paper
    - Implement uploadCqAnswer(Request $request, Exam $exam) handling file uploads
    - Implement viewCqSubmission(CqSubmission $submission) showing submission status
    - _Requirements: 4.1-4.6_
  
  - [~] 4.4 Implement results methods in StudentPortalController
    - Implement results(Request $request) with filtering by type and date
    - Implement downloadMarkSheet(ExamResult $result) generating PDF
    - Implement performanceTrends() returning chart data for performance over time
    - _Requirements: 5.1, 5.2, 5.3, 5.5_
  
  - [~] 4.5 Add Student Portal routes
    - Add route group 'student' with auth middleware
    - Add routes: dashboard, materials, materials/{material}/download, schedule
    - Add routes: exams, exams/{exam}/start, exams/{exam}/submit, exams/attempt/{attempt}/save-answer
    - Add routes: exams/{exam}/cq, exams/{exam}/cq/upload, results, results/{result}/mark-sheet
    - Add routes: payments, payments/{payment}/receipt
    - _Requirements: All Student Portal requirements_

- [ ] 5. Student Portal - Views
  - [~] 5.1 Create student dashboard view
    - Create resources/views/student/dashboard.blade.php
    - Display batch/course info, payment status alert, attendance percentage
    - Display upcoming exams with countdown, recent results
    - Use Tailwind CSS consistent with existing admin views
    - _Requirements: 1.1-1.5_
  
  - [~] 5.2 Create student materials view
    - Create resources/views/student/materials.blade.php
    - Display materials grouped by type (PDF, video, document, link)
    - Show upload date and description for each material
    - Add download/view buttons based on material type
    - _Requirements: 2.1-2.4_
  
  - [~] 5.3 Create MCQ exam taking view
    - Create resources/views/student/exam-take.blade.php
    - Display countdown timer with JavaScript
    - Show questions with radio buttons for MCQ options
    - Implement auto-save on answer selection via AJAX
    - Add submit button and navigation warning
    - _Requirements: 3.1-3.6_
  
  - [~] 5.4 Create exam result view
    - Create resources/views/student/exam-result.blade.php
    - Display score, grade, rank
    - Show questions with student answers, correct answers, and explanations
    - Add download mark sheet button
    - _Requirements: 3.4, 3.5, 5.1_
  
  - [~] 5.5 Create CQ exam views
    - Create resources/views/student/cq-exam.blade.php for viewing question paper
    - Create resources/views/student/cq-upload.blade.php for answer upload form
    - Support multiple file uploads with drag-and-drop
    - Show submission status and feedback when evaluated
    - _Requirements: 4.1-4.6_
  
  - [~] 5.6 Create student results and payments views
    - Create resources/views/student/results.blade.php with filtering
    - Create resources/views/student/payments.blade.php with payment history
    - Create resources/views/student/schedule.blade.php with weekly timetable
    - _Requirements: 5.1, 5.2, 6.1-6.4, 15.5_

- [ ] 6. Checkpoint - Test Student Portal
  - Verify student can log in and see dashboard
  - Test materials access and download
  - Test MCQ exam flow (start, answer, submit)
  - Test CQ exam upload
  - Verify results and payments display
  - Ensure all tests pass, ask the user if questions arise

- [ ] 7. Accounts Module - Services and Controller
  - [~] 7.1 Create AccountService
    - Implement createExpense(array $data) with validation
    - Implement updateExpense(Expense $expense, array $data)
    - Implement deleteExpense(Expense $expense)
    - Implement getExpenses(array $filters) with pagination and filtering
    - _Requirements: 7.1-7.5_
  
  - [~] 7.2 Extend AccountService for Income
    - Implement recordIncome(array $data) for manual income entry
    - Implement recordPaymentIncome(Payment $payment) for automatic income from payments
    - Implement getIncome(array $filters) with pagination and filtering
    - Add observer on Payment model to auto-create income on completion
    - _Requirements: 8.1-8.4_
  
  - [~] 7.3 Implement financial reports in AccountService
    - Implement getDailySummary(Carbon $date) returning income/expense totals
    - Implement getMonthlySummary(int $year, int $month) with category breakdowns
    - Implement getFinancialReport(Carbon $startDate, Carbon $endDate) with profit/loss
    - Implement exportToExcel(array $filters) using Maatwebsite Excel
    - Implement exportToPdf(array $filters) using DomPDF
    - _Requirements: 9.1, 9.2, 9.4, 9.5_
  
  - [~] 7.4 Update Admin AccountController
    - Implement index() showing financial overview
    - Implement income() with CRUD for income records
    - Implement expenses() with CRUD for expense records
    - Implement reports() with date range selection and charts
    - Implement export() for Excel/PDF download
    - Add store, update, destroy methods for expenses and income
    - _Requirements: 7.1-7.5, 8.1-8.4, 9.1-9.5_
  
  - [~] 7.5 Add Account routes
    - Add routes for expense CRUD: index, store, update, destroy
    - Add routes for income CRUD: index, store, update, destroy
    - Add routes for reports: daily, monthly, custom range
    - Add routes for export: excel, pdf
    - _Requirements: All Accounts requirements_

- [ ] 8. Accounts Module - Views
  - [~] 8.1 Update accounts views
    - Update resources/views/dashboard/accounts/index.blade.php with overview dashboard
    - Update resources/views/dashboard/accounts/income.blade.php with income list and form
    - Update resources/views/dashboard/accounts/expenses.blade.php with expense list and form
    - Update resources/views/dashboard/accounts/reports.blade.php with charts and filters
    - Update resources/views/dashboard/accounts/export.blade.php with export options
    - _Requirements: 7.1-7.5, 8.1-8.4, 9.1-9.5_

- [ ] 9. Inventory Module
  - [~] 9.1 Create InventoryService
    - Implement createItem(array $data) with validation
    - Implement updateItem(InventoryItem $item, array $data)
    - Implement deleteItem(InventoryItem $item)
    - Implement getItems(array $filters) with pagination
    - Implement recordPurchase(InventoryItem $item, array $data) updating quantity
    - Implement recordUsage(InventoryItem $item, array $data) decreasing quantity
    - Implement getLowStockItems(int $threshold) returning items below threshold
    - Implement getTransactionHistory(InventoryItem $item)
    - Implement getInventoryReport() with total stock values
    - _Requirements: 10.1-10.6_
  
  - [~] 9.2 Create Admin InventoryController
    - Implement index() listing all items with low stock alerts
    - Implement create() and store() for new items
    - Implement edit() and update() for existing items
    - Implement destroy() for deleting items
    - Implement purchase(Request $request, InventoryItem $item) for recording purchases
    - Implement usage(Request $request, InventoryItem $item) for recording usage
    - Implement history(InventoryItem $item) showing transaction history
    - Implement report() showing inventory report
    - _Requirements: 10.1-10.6_
  
  - [~] 9.3 Add Inventory routes and views
    - Add resource routes for inventory items
    - Add routes: purchase, usage, history, report
    - Create resources/views/dashboard/inventory/index.blade.php
    - Create resources/views/dashboard/inventory/create.blade.php
    - Create resources/views/dashboard/inventory/edit.blade.php
    - Create resources/views/dashboard/inventory/show.blade.php with transaction history
    - Create resources/views/dashboard/inventory/report.blade.php
    - _Requirements: 10.1-10.6_

- [ ] 10. Checkpoint - Test Accounts and Inventory
  - Test expense CRUD operations
  - Test income tracking and auto-creation from payments
  - Test financial reports and exports
  - Test inventory CRUD and stock tracking
  - Test low stock alerts
  - Ensure all tests pass, ask the user if questions arise

- [ ] 11. Communication Module
  - [~] 11.1 Create EmailNotificationService
    - Implement sendPaymentConfirmation(Payment $payment) using Mail facade
    - Implement sendResultNotification(ExamResult $result)
    - Implement sendAttendanceAlert(Student $student, float $percentage)
    - Implement sendBulkEmail(array $recipients, string $subject, string $body)
    - Implement getTemplate(string $type) and renderTemplate(string $template, array $variables)
    - Create email templates in resources/views/emails/
    - _Requirements: 11.1-11.5_
  
  - [~] 11.2 Create email Mailable classes
    - Create App\Mail\PaymentConfirmation mailable
    - Create App\Mail\ResultNotification mailable
    - Create App\Mail\AttendanceAlert mailable
    - Configure mail settings in config/mail.php
    - _Requirements: 11.1-11.3_
  
  - [~] 11.3 Add email logging
    - Create email_logs table migration with: recipient, subject, body, status, sent_at, error
    - Create EmailLog model
    - Update EmailNotificationService to log all sent emails
    - Add email logs view in admin communication section
    - _Requirements: 11.5_
  
  - [~] 11.4 Create AnnouncementController
    - Implement index() listing all announcements
    - Implement create() and store() for new announcements
    - Implement edit() and update() for existing announcements
    - Implement destroy() for deleting announcements
    - Add announcement display to student dashboard
    - _Requirements: 13.1-13.4_
  
  - [~] 11.5 Add announcement routes and views
    - Add resource routes for announcements
    - Create resources/views/dashboard/announcements/index.blade.php
    - Create resources/views/dashboard/announcements/create.blade.php
    - Create resources/views/dashboard/announcements/edit.blade.php
    - Update student dashboard to show active announcements
    - _Requirements: 13.1-13.4_
  
  - [~] 11.6 Update batch form for group links
    - Add telegram_link and facebook_link fields to batch create/edit forms
    - Display group links in student portal dashboard
    - _Requirements: 12.1-12.3_

- [ ] 12. Course Materials CRUD
  - [~] 12.1 Create Admin MaterialController
    - Implement index(Course $course) listing materials for a course
    - Implement create(Course $course) showing upload form
    - Implement store(Request $request, Course $course) handling file upload
    - Implement edit(CourseMaterial $material) showing edit form
    - Implement update(Request $request, CourseMaterial $material)
    - Implement destroy(CourseMaterial $material) deleting file and record
    - Implement reorder(Request $request, Course $course) updating material order
    - Validate file types (pdf, video, image) and sizes
    - _Requirements: 14.1-14.5_
  
  - [~] 12.2 Add material routes and views
    - Add nested resource routes under courses for materials
    - Add reorder route for drag-and-drop ordering
    - Create resources/views/dashboard/materials/index.blade.php
    - Create resources/views/dashboard/materials/create.blade.php
    - Create resources/views/dashboard/materials/edit.blade.php
    - Add drag-and-drop reordering with JavaScript
    - _Requirements: 14.1-14.5_

- [ ] 13. Schedule Management
  - [~] 13.1 Create Admin ScheduleController
    - Implement index() showing all schedules with weekly view
    - Implement create() and store() for new schedules
    - Implement edit() and update() for existing schedules
    - Implement destroy() for deleting schedules
    - Implement checkConflict(Request $request) for AJAX conflict checking
    - Add conflict validation in store/update (same room, day, overlapping time)
    - _Requirements: 15.1-15.4_
  
  - [~] 13.2 Add schedule routes and views
    - Add resource routes for schedules
    - Add conflict-check AJAX route
    - Create resources/views/dashboard/schedules/index.blade.php with weekly timetable
    - Create resources/views/dashboard/schedules/create.blade.php
    - Create resources/views/dashboard/schedules/edit.blade.php
    - Display schedules in a weekly grid format
    - _Requirements: 15.1-15.4_
  
  - [~] 13.3 Add exam routine view
    - Create resources/views/dashboard/exams/routine.blade.php
    - Display exams with name, date, time, duration
    - Add filtering by batch and date range
    - Add route for exam routine
    - _Requirements: 16.1-16.3_

- [ ] 14. Teacher Salary Management
  - [~] 14.1 Create Admin SalaryController
    - Implement index() listing all salary payments
    - Implement create() showing payment form with teacher dropdown
    - Implement store(Request $request) with duplicate check (teacher + month)
    - Implement edit() and update() for existing payments
    - Implement destroy() for deleting payments
    - Implement history(Teacher $teacher) showing payment history
    - Implement report(Request $request) with monthly aggregation
    - _Requirements: 17.1-17.5_
  
  - [~] 14.2 Add salary routes and views
    - Add resource routes for teacher salaries
    - Add history and report routes
    - Create resources/views/dashboard/salaries/index.blade.php
    - Create resources/views/dashboard/salaries/create.blade.php
    - Create resources/views/dashboard/salaries/edit.blade.php
    - Create resources/views/dashboard/salaries/history.blade.php
    - Create resources/views/dashboard/salaries/report.blade.php
    - _Requirements: 17.1-17.5_

- [ ] 15. Checkpoint - Test Communication and Management Features
  - Test email notifications (use mail fake in testing)
  - Test announcements CRUD and targeting
  - Test course materials upload and reordering
  - Test class schedule CRUD and conflict detection
  - Test teacher salary management
  - Ensure all tests pass, ask the user if questions arise

- [ ] 16. Admin Enhancements - Backup
  - [~] 16.1 Create BackupService
    - Implement createBackup() using mysqldump or Laravel backup package
    - Implement restoreBackup(string $filename) restoring from SQL file
    - Implement getBackupList() returning available backups with metadata
    - Implement deleteBackup(string $filename)
    - Implement downloadBackup(string $filename) returning file response
    - Store backups in storage/app/backups/
    - _Requirements: 18.1-18.4_
  
  - [~] 16.2 Create Admin BackupController
    - Implement index() listing all backups
    - Implement create() initiating new backup
    - Implement restore(Request $request) restoring selected backup
    - Implement download(string $filename) downloading backup file
    - Implement destroy(string $filename) deleting backup
    - Restrict to super-admin role
    - _Requirements: 18.1-18.4_
  
  - [~] 16.3 Add backup routes and views
    - Add routes: index, create, restore, download, destroy
    - Create resources/views/dashboard/backups/index.blade.php
    - Show backup list with date, size, download/restore/delete buttons
    - Add confirmation dialogs for restore and delete
    - _Requirements: 18.1-18.4_

- [ ] 17. Admin Enhancements - Activity Logging
  - [~] 17.1 Create ActivityLogService
    - Implement log(string $action, ?Model $model, ?array $changes) creating log entry
    - Implement getLogs(array $filters) with pagination and filtering
    - Create model observers for automatic logging on create/update/delete
    - Register observers in AppServiceProvider for key models
    - _Requirements: 19.1-19.3_
  
  - [~] 17.2 Create Admin ActivityLogController
    - Implement index(Request $request) with filtering by user, action, date
    - Display logs with user, action, model type, timestamp, changes
    - Restrict to super-admin role
    - _Requirements: 19.1-19.3_
  
  - [~] 17.3 Add activity log routes and views
    - Add route for activity logs index
    - Create resources/views/dashboard/activity-logs/index.blade.php
    - Show filterable log list with expandable change details
    - _Requirements: 19.1-19.3_

- [ ] 18. Admin Enhancements - Data Import
  - [~] 18.1 Create ImportService
    - Implement parseFile(UploadedFile $file) supporting CSV and Excel
    - Implement validateData(array $data, string $type) checking required fields
    - Implement previewImport(array $data, string $type) returning preview with errors
    - Implement executeImport(array $data, string $type, array $mapping) creating records
    - Implement importStudents(array $data, array $mapping) for student bulk import
    - Use Maatwebsite Excel for file parsing
    - _Requirements: 20.1-20.4_
  
  - [~] 18.2 Create Admin ImportController
    - Implement index() showing import options
    - Implement upload(Request $request) parsing file and showing preview
    - Implement preview(Request $request) displaying data with validation errors
    - Implement execute(Request $request) committing import with mapping
    - Support student import initially, extensible for other types
    - _Requirements: 20.1-20.4_
  
  - [~] 18.3 Add import routes and views
    - Add routes: index, upload, preview, execute
    - Create resources/views/dashboard/import/index.blade.php with file upload
    - Create resources/views/dashboard/import/preview.blade.php with data preview
    - Show column mapping interface and validation errors
    - Add progress indicator for large imports
    - _Requirements: 20.1-20.4_

- [ ] 19. Final Integration and Navigation
  - [~] 19.1 Update sidebar navigation
    - Add Student Portal link for student role users
    - Add Inventory section under admin menu
    - Add Announcements under Communication
    - Add Backups and Activity Logs under Settings (super-admin only)
    - Add Import under Tools section
    - Update SidebarService with new menu items
  
  - [~] 19.2 Add scheduled tasks
    - Create command for auto-submitting expired exams
    - Create command for sending attendance alerts
    - Register commands in console/Kernel.php or routes/console.php
    - Configure schedule for daily/hourly runs
  
  - [~] 19.3 Update middleware and authorization
    - Ensure student routes are protected for student role
    - Ensure admin routes are protected for admin/super-admin roles
    - Add policy classes for new models if needed

- [ ] 20. Final Checkpoint - Complete System Test
  - Test complete student portal flow
  - Test all admin CRUD operations
  - Test financial reports and exports
  - Test backup and restore
  - Test activity logging
  - Test data import
  - Verify all navigation links work
  - Ensure all tests pass, ask the user if questions arise

## Notes

- Tasks are ordered to build incrementally with Student Portal first (highest priority)
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Use existing patterns from the codebase (services, Blade views, Tailwind CSS)
- Leverage existing models where available (CourseMaterial, ClassSchedule, TeacherSalary)

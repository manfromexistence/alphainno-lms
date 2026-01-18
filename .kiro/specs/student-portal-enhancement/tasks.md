# Implementation Plan: Student Portal Enhancement

## Overview

This implementation plan focuses on rapidly connecting existing Laravel components and adding new UI interfaces for the student portal. The approach prioritizes practical implementation by leveraging existing models (Student, Exam, ExamAttempt, ExamResult, Course, Batch) and adding new functionality through controllers, views, and a new Payment model. Tasks are organized to build incrementally, with core functionality first, followed by enhancements.

## Tasks

- [x] 1. Set up database migrations and Payment model
  - Create migration for payments table with all required fields (student_id, course_id, payment_method, transaction_id, screenshot_path, amount, status, timestamps)
  - Create migration to add cheating_events JSON column to exam_attempts table
  - Create migration to add screenshots JSON column to exam_attempts table
  - Create Payment model with relationships, constants, and helper methods
  - Run migrations to update database schema
  - _Requirements: 12.4, 6.4, 3.4_

- [ ] 2. Implement student exam access control
  - [x] 2.1 Create StudentExamAccessMiddleware to restrict student access to admin exam functions
    - Check user role and redirect students attempting to access edit/view URLs
    - Apply middleware to exam routes
    - _Requirements: 1.2_
  
  - [x] 2.2 Update student exam list view to show only "Take Exam" actions
    - Modify exam list Blade template to hide edit/view/delete buttons for students
    - Show only "Take Exam" button for available exams
    - _Requirements: 1.1, 1.3_

- [ ] 3. Build MCQ exam interface
  - [x] 3.1 Create ExamTimeValidator service class
    - Implement canStartExam(), getTimeStatus(), and getRemainingTime() methods
    - Add validation logic for exam time windows
    - _Requirements: 5.1, 5.2_
  
  - [x] 3.2 Create ExamController::takeMCQ() method
    - Validate exam time window using ExamTimeValidator
    - Create or retrieve ExamAttempt for student
    - Load exam questions with options
    - Calculate remaining time
    - Pass data to view
    - _Requirements: 2.1, 5.1, 5.2_
  
  - [-] 3.3 Create mcq-exam.blade.php view with timer and navigation
    - Display countdown timer with remaining time
    - Add question navigation controls (next/previous/jump to question)
    - Show progress indicators for answered/unanswered questions
    - Display MCQ questions with radio button options
    - Add submit button
    - _Requirements: 2.1, 2.3, 2.5_
  
  - [~] 3.4 Implement JavaScript for MCQ exam functionality
    - Create timer countdown with auto-submit at zero
    - Implement AJAX auto-save for answer selections
    - Add question navigation handlers
    - Update progress indicators when answers change
    - Implement fullscreen mode request and exit detection
    - Add tab switch detection
    - Send cheating events to server via AJAX
    - _Requirements: 2.2, 2.4, 6.1, 6.2, 6.3_
  
  - [~] 3.5 Create ExamController::submitExam() method
    - Validate exam attempt ownership
    - Save all answers to exam_attempts table
    - Create ExamResult record with score calculation
    - Mark attempt as submitted
    - Redirect to results page
    - _Requirements: 2.2_
  
  - [~] 3.6 Create ExamController::logCheatingEvent() method
    - Validate exam attempt ownership
    - Append event to cheating_events JSON column
    - Return success response
    - _Requirements: 6.2, 6.3, 6.4_

- [ ] 4. Build CQ exam interface
  - [~] 4.1 Create ExamController::takeCQ() method
    - Validate exam time window
    - Create or retrieve ExamAttempt
    - Load CQ questions
    - Pass data to view
    - _Requirements: 5.1, 5.2_
  
  - [~] 4.2 Create cq-exam.blade.php view with text editor and upload
    - Display CQ questions with text editor for each (use TinyMCE or similar)
    - Add screenshot upload field for each question
    - Include timer and submit button
    - _Requirements: 3.1, 3.2_
  
  - [~] 4.3 Create ExamController::uploadScreenshot() method
    - Validate file type (jpg/png/pdf) and size (max 5MB)
    - Store file in storage/app/exam-screenshots
    - Update exam_attempts screenshots JSON column
    - Return success response with file path
    - _Requirements: 3.3, 3.4_
  
  - [~] 4.4 Add JavaScript for CQ exam functionality
    - Implement file upload with validation
    - Add auto-save for text answers
    - Include timer and anti-cheating measures (reuse from MCQ)
    - _Requirements: 3.3, 6.1, 6.2, 6.3_

- [ ] 5. Build live exam interface
  - [~] 5.1 Create ExamController::takeLive() method
    - Validate exam is currently live
    - Create ExamAttempt
    - Load questions
    - Pass live status to view
    - _Requirements: 4.2_
  
  - [~] 5.2 Create live-exam.blade.php view
    - Display "LIVE" badge prominently
    - Show exam interface similar to MCQ with real-time indicators
    - Include timer and navigation
    - _Requirements: 4.3_
  
  - [~] 5.3 Add real-time synchronization for live exam responses
    - Implement AJAX polling or WebSocket for response sync
    - Update UI to show real-time status
    - _Requirements: 4.2_

- [ ] 6. Implement automatic exam submission
  - [~] 6.1 Create scheduled job to auto-submit expired exams
    - Create AutoSubmitExpiredExams job
    - Query for active exam attempts past end time
    - Submit each expired attempt
    - Schedule to run every minute
    - _Requirements: 5.3_

- [ ] 7. Build exam results display
  - [~] 7.1 Create ExamController::viewResults() method
    - Retrieve ExamResult for authenticated student
    - Load exam, attempt, questions, and answers
    - Calculate performance metrics (score, percentage, time taken, accuracy)
    - Fetch student's leaderboard rank
    - Pass data to view
    - _Requirements: 7.1, 7.2, 7.4_
  
  - [~] 7.2 Create exam-results.blade.php view
    - Display header with score, percentage, pass/fail status
    - Show performance metrics (time taken, accuracy, rank)
    - Display question-by-question breakdown
    - For MCQ: highlight correct answers (green) and incorrect answers (red)
    - For CQ: show submitted text and screenshots
    - Add "Download PDF" button
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 8.1_

- [ ] 8. Implement PDF export for exam results
  - [~] 8.1 Install barryvdh/laravel-dompdf package
    - Add package via composer
    - Publish configuration if needed
    - _Requirements: 8.2_
  
  - [~] 8.2 Create ExamResultPdfGenerator service class
    - Implement generate() method to create PDF from ExamResult
    - Create pdf/exam-result.blade.php template for PDF layout
    - Include student info, exam details, scores, performance analysis
    - _Requirements: 8.2, 8.3_
  
  - [~] 8.3 Create ExamController::downloadResultPdf() method
    - Retrieve ExamResult for student
    - Call PdfGenerator service
    - Return PDF download response
    - _Requirements: 8.2_

- [ ] 9. Build exam leaderboard
  - [~] 9.1 Create ExamController::leaderboard() method
    - Query ExamResults for the exam
    - Order by score DESC, time_taken ASC
    - Add rank numbers
    - Identify current student's position
    - Pass data to view
    - _Requirements: 9.1, 9.2_
  
  - [~] 9.2 Create exam-leaderboard.blade.php view
    - Display leaderboard table with rank, name, score, time
    - Highlight current student's row
    - Add medal icons for top 3 positions
    - _Requirements: 9.1, 9.2, 9.3_

- [~] 10. Checkpoint - Test exam functionality
  - Ensure all exam interfaces work correctly, timer functions properly, and results display accurately. Ask the user if questions arise.

- [ ] 11. Build course browsing interface
  - [~] 11.1 Create CourseController::browse() method
    - Fetch all active courses
    - Get authenticated student's enrolled course IDs
    - Get course IDs with pending payments
    - Pass data to view
    - _Requirements: 10.1_
  
  - [~] 11.2 Create courses.blade.php view
    - Display course cards with title, description, instructor, fee
    - Show "Buy Course" button for unenrolled courses
    - Show "Enrolled" badge for enrolled courses
    - Show "Payment Pending" status for courses with pending payments
    - _Requirements: 10.1, 10.2, 10.3, 10.4_

- [ ] 12. Build payment submission workflow
  - [~] 12.1 Create PaymentController::showForm() method
    - Load course details
    - Load payment methods from config or database
    - Pass data to view
    - _Requirements: 11.1, 11.2_
  
  - [~] 12.2 Create payment-form.blade.php view
    - Display course information
    - Show payment method options (bKash, Nagad, Rocket, Bank Transfer)
    - Display payment instructions for selected method
    - Add form fields: transaction ID, screenshot upload, amount, notes
    - Add submit button
    - _Requirements: 11.1, 11.2, 11.3_
  
  - [~] 12.3 Create PaymentController::submit() method
    - Validate all form inputs (course_id, payment_method, transaction_id, screenshot, amount)
    - Validate screenshot file format (jpg/png/pdf) and size (max 5MB)
    - Store screenshot in storage/app/payment-screenshots
    - Create Payment record with status 'pending'
    - Redirect to payment dashboard with success message
    - _Requirements: 12.1, 12.2, 12.3, 12.4_

- [ ] 13. Build admin payment review interface
  - [~] 13.1 Create PaymentController::reviewList() method
    - Fetch all pending payments
    - Eager load student and course relationships
    - Pass data to view
    - _Requirements: 13.1_
  
  - [~] 13.2 Create admin/payment-review.blade.php view for list
    - Display table of pending payments
    - Show student name, course, amount, submission date
    - Add "Review" button for each payment
    - _Requirements: 13.1_
  
  - [~] 13.3 Create PaymentController::reviewDetail() method
    - Load payment with student and course relationships
    - Pass data to detail view
    - _Requirements: 13.2_
  
  - [~] 13.4 Create admin/payment-detail.blade.php view
    - Display screenshot preview
    - Show student details (name, email, ID)
    - Show course information
    - Show payment details (method, transaction ID, amount)
    - Add approve and reject buttons
    - Add admin notes textarea
    - _Requirements: 13.2_
  
  - [~] 13.5 Create PaymentController::approve() method
    - Validate payment is pending
    - Update payment status to 'approved'
    - Add student to course batch (create enrollment)
    - Record reviewer ID and timestamp
    - Send notification to student (email or in-app)
    - Redirect with success message
    - _Requirements: 13.3_
  
  - [~] 13.6 Create PaymentController::reject() method
    - Validate payment is pending
    - Update payment status to 'rejected'
    - Save admin notes
    - Record reviewer ID and timestamp
    - Send notification to student
    - Redirect with success message
    - _Requirements: 13.4_

- [ ] 14. Build student payment dashboard
  - [~] 14.1 Create PaymentController::dashboard() method
    - Fetch student's enrollments with courses
    - Fetch all payments for student
    - Calculate totals: total fees, amount deposited, pending amount
    - Group payments by course
    - Pass data to view
    - _Requirements: 14.1, 14.2, 14.3_
  
  - [~] 14.2 Create payment-dashboard.blade.php view
    - Display summary cards (total courses, total fees, total paid, total pending)
    - Show course-wise breakdown with fee, deposited, pending
    - Display payment history table with date, course, method, transaction ID, amount, status
    - Add status badges (pending/approved/rejected) with appropriate colors
    - Show admin notes for rejected payments
    - Add link to view screenshot for each payment
    - _Requirements: 14.1, 14.2, 14.3, 14.4, 14.5_

- [~] 15. Add routes for all new functionality
  - Add student exam routes (take, submit, results, leaderboard, log cheating events)
  - Add course browsing routes
  - Add payment routes (form, submit, dashboard)
  - Add admin payment review routes (list, detail, approve, reject)
  - Apply appropriate middleware (auth, student, admin)
  - _Requirements: All_

- [~] 16. Create configuration for payment methods
  - Create config/payment-methods.php with payment method details
  - Include method names, account numbers, and instructions
  - _Requirements: 11.2_

- [~] 17. Final checkpoint - Integration testing
  - Test complete student workflow: browse courses, take exams, submit payments
  - Test complete admin workflow: review and approve/reject payments
  - Verify all UI components render correctly
  - Test file uploads and downloads
  - Verify database relationships and data integrity
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- This implementation leverages existing Laravel models and focuses on connecting components
- No automated tests are included per requirements - testing will be manual and integration-focused
- Tasks build incrementally: database setup → exam interfaces → results → payment workflow
- Each task references specific requirements for traceability
- Checkpoints ensure validation at key milestones
- File uploads use Laravel's storage system with validation
- Payment approval automatically creates course enrollment
- Anti-cheating measures log events but don't prevent exam submission

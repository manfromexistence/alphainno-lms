# Requirements Document

## Introduction

This document specifies the requirements for completing all missing features in the Laravel LMS project. The system currently has partial implementations for admin management but lacks critical student-facing functionality, financial management, and several operational modules. This spec covers the Student Portal, Online Exam Interface, Accounts & Finance, Inventory Management, Email Notifications, and various administrative enhancements.

## Glossary

- **Student_Portal**: The student-facing interface where enrolled students access their dashboard, materials, exams, and results
- **Exam_Engine**: The system component that manages exam delivery, timing, auto-submission, and result calculation
- **CQ_Exam**: Creative Question exam type requiring written/uploaded answers evaluated by teachers
- **MCQ_Exam**: Multiple Choice Question exam type with automatic grading
- **Accounts_Module**: The financial management system tracking income, expenses, and generating reports
- **Inventory_System**: The module managing physical assets, stock levels, and purchase history
- **Email_Service**: The notification system for sending emails to students, guardians, and staff
- **Material_Manager**: The component handling course material uploads and organization
- **Schedule_Manager**: The system managing class schedules and exam routines
- **Salary_Manager**: The module handling teacher salary payments and records
- **Mark_Sheet_Generator**: The component producing printable student result documents

## Requirements

### Requirement 1: Student Dashboard

**User Story:** As a student, I want to access a personalized dashboard, so that I can view my class schedule, payment status, and academic progress at a glance.

#### Acceptance Criteria

1. WHEN a student logs in, THE Student_Portal SHALL display a dashboard with class/exam routine, payment status summary, and recent exam marks
2. WHEN viewing the dashboard, THE Student_Portal SHALL show the student's enrolled batch and course information
3. WHEN the student has pending payments, THE Student_Portal SHALL display a payment due alert with amount and due date
4. WHEN the student has upcoming exams, THE Student_Portal SHALL display exam schedule with countdown to next exam
5. THE Student_Portal SHALL display attendance percentage for the current month

### Requirement 2: Study Materials Access

**User Story:** As a student, I want to access study materials for my enrolled courses, so that I can prepare for classes and exams.

#### Acceptance Criteria

1. WHEN a student navigates to materials section, THE Student_Portal SHALL display all materials for their enrolled course
2. THE Student_Portal SHALL organize materials by type (PDF, video, document, link)
3. WHEN a student clicks on a material, THE Student_Portal SHALL open or download the file appropriately
4. THE Student_Portal SHALL show material upload date and description
5. IF a student is not enrolled in a course, THEN THE Student_Portal SHALL deny access to that course's materials

### Requirement 3: MCQ Exam Interface

**User Story:** As a student, I want to take MCQ exams online with a countdown timer, so that I can complete timed assessments from anywhere.

#### Acceptance Criteria

1. WHEN a student starts an MCQ exam, THE Exam_Engine SHALL display questions with a countdown timer showing remaining time
2. WHEN the timer reaches zero, THE Exam_Engine SHALL auto-submit the exam with current answers
3. WHEN a student selects an answer, THE Exam_Engine SHALL save the selection immediately
4. WHEN a student submits the exam, THE Exam_Engine SHALL calculate and display the score with correct answers
5. THE Exam_Engine SHALL display answer explanations after submission if provided
6. WHEN an exam is in progress, THE Exam_Engine SHALL prevent navigation away without confirmation
7. THE Exam_Engine SHALL track time spent on each question for analytics

### Requirement 4: CQ Exam Workflow

**User Story:** As a student, I want to view CQ exam questions and upload my answer scripts, so that I can complete written assessments online.

#### Acceptance Criteria

1. WHEN a CQ exam is available, THE Exam_Engine SHALL display the question paper (PDF/Image attachment)
2. THE Exam_Engine SHALL allow students to upload answer scripts as PDF or image files
3. WHEN a student uploads an answer, THE Exam_Engine SHALL validate file type and size limits
4. THE Exam_Engine SHALL allow multiple file uploads for a single CQ exam
5. WHEN a teacher evaluates a CQ submission, THE Exam_Engine SHALL store marks and feedback
6. THE Exam_Engine SHALL aggregate CQ and MCQ marks for combined result display

### Requirement 5: Result History and Mark Sheets

**User Story:** As a student, I want to view my exam history and download mark sheets, so that I can track my academic progress.

#### Acceptance Criteria

1. WHEN a student views results, THE Student_Portal SHALL display all past exam results with marks, grade, and rank
2. THE Student_Portal SHALL allow filtering results by exam type (MCQ/CQ) and date range
3. WHEN a student requests a mark sheet, THE Mark_Sheet_Generator SHALL produce a printable PDF document
4. THE Mark_Sheet_Generator SHALL include student details, exam details, marks obtained, and grade
5. THE Student_Portal SHALL display performance trends over time

### Requirement 6: Student Payment View

**User Story:** As a student, I want to view my payment history and pending dues, so that I can manage my fee payments.

#### Acceptance Criteria

1. WHEN a student views payments, THE Student_Portal SHALL display total fees, paid amount, and due amount
2. THE Student_Portal SHALL list all payment transactions with date, amount, and receipt number
3. WHEN a payment is completed, THE Student_Portal SHALL allow downloading the payment receipt
4. THE Student_Portal SHALL show payment due dates and overdue status

### Requirement 7: Expense Management

**User Story:** As an administrator, I want to record and categorize expenses, so that I can track organizational spending.

#### Acceptance Criteria

1. THE Accounts_Module SHALL allow creating expense records with amount, category, date, and description
2. THE Accounts_Module SHALL support expense categories: rent, salary, bills, advertisement, furniture, paper, stationary
3. WHEN an expense is created, THE Accounts_Module SHALL validate required fields
4. THE Accounts_Module SHALL allow editing and deleting expense records
5. THE Accounts_Module SHALL display expenses in a paginated list with filtering by category and date

### Requirement 8: Income Tracking

**User Story:** As an administrator, I want to track all income sources, so that I can monitor revenue streams.

#### Acceptance Criteria

1. THE Accounts_Module SHALL automatically record income from student payments (admission fees, class fees)
2. THE Accounts_Module SHALL allow manual income entry for other sources (materials sales, etc.)
3. THE Accounts_Module SHALL categorize income by type (admission, tuition, materials, other)
4. THE Accounts_Module SHALL display income records with source, amount, date, and student reference if applicable

### Requirement 9: Financial Reports

**User Story:** As an administrator, I want to generate financial reports, so that I can analyze organizational finances.

#### Acceptance Criteria

1. THE Accounts_Module SHALL generate daily income/expense summary reports
2. THE Accounts_Module SHALL generate monthly financial reports with totals and category breakdowns
3. THE Accounts_Module SHALL display income vs expense charts for visual analysis
4. WHEN exporting reports, THE Accounts_Module SHALL support Excel and PDF formats
5. THE Accounts_Module SHALL calculate profit/loss for selected periods

### Requirement 10: Inventory Management

**User Story:** As an administrator, I want to manage inventory items, so that I can track organizational assets and supplies.

#### Acceptance Criteria

1. THE Inventory_System SHALL allow creating inventory items with name, category, quantity, and unit price
2. THE Inventory_System SHALL track stock levels and display current quantities
3. WHEN stock falls below a threshold, THE Inventory_System SHALL display low stock alerts
4. THE Inventory_System SHALL record purchase history with date, quantity, supplier, and cost
5. THE Inventory_System SHALL record usage/consumption with date, quantity, and purpose
6. THE Inventory_System SHALL generate inventory reports with current stock values

### Requirement 11: Email Notifications

**User Story:** As an administrator, I want to send email notifications, so that I can communicate important information to students and guardians.

#### Acceptance Criteria

1. WHEN a payment is completed, THE Email_Service SHALL send a confirmation email to the student
2. WHEN exam results are published, THE Email_Service SHALL send result notification emails to students
3. WHEN attendance falls below threshold, THE Email_Service SHALL send alert emails to guardians
4. THE Email_Service SHALL support email templates with variable placeholders
5. THE Email_Service SHALL log all sent emails with status and timestamp

### Requirement 12: Communication Group Links

**User Story:** As an administrator, I want to manage Telegram/Facebook group links per batch, so that students can join relevant communication channels.

#### Acceptance Criteria

1. THE Student_Portal SHALL display batch-specific Telegram and Facebook group links
2. THE Admin_Panel SHALL allow setting group links per batch/course
3. WHEN a student views their batch, THE Student_Portal SHALL show join links for communication groups

### Requirement 13: Announcements System

**User Story:** As an administrator, I want to post announcements, so that I can broadcast notices to students.

#### Acceptance Criteria

1. THE Admin_Panel SHALL allow creating announcements with title, content, and target audience (all/batch/course)
2. THE Student_Portal SHALL display relevant announcements on the dashboard
3. THE Admin_Panel SHALL allow setting announcement expiry dates
4. WHEN an announcement is active, THE Student_Portal SHALL highlight it prominently

### Requirement 14: Course Material CRUD

**User Story:** As an administrator, I want to upload and manage course materials, so that students can access learning resources.

#### Acceptance Criteria

1. THE Material_Manager SHALL allow uploading materials with title, description, type, and file
2. THE Material_Manager SHALL support PDF, video, image, and external link types
3. THE Material_Manager SHALL allow reordering materials within a course
4. THE Material_Manager SHALL validate file sizes and types before upload
5. THE Material_Manager SHALL allow editing and deleting materials

### Requirement 15: Class Schedule Management

**User Story:** As an administrator, I want to manage class schedules, so that students and teachers know when classes occur.

#### Acceptance Criteria

1. THE Schedule_Manager SHALL allow creating class schedules with batch, day, time, room, and subject
2. THE Schedule_Manager SHALL display weekly timetable view per batch
3. THE Schedule_Manager SHALL prevent scheduling conflicts (same room, same time)
4. THE Schedule_Manager SHALL allow editing and deleting schedules
5. THE Student_Portal SHALL display the student's class schedule

### Requirement 16: Exam Routine Management

**User Story:** As an administrator, I want to manage exam schedules, so that students can prepare for upcoming exams.

#### Acceptance Criteria

1. THE Schedule_Manager SHALL display exam routine with exam name, date, time, and duration
2. THE Schedule_Manager SHALL allow filtering exam routine by batch and date range
3. THE Student_Portal SHALL display upcoming exams in the student's exam routine

### Requirement 17: Teacher Salary Management

**User Story:** As an administrator, I want to manage teacher salary payments, so that I can track compensation records.

#### Acceptance Criteria

1. THE Salary_Manager SHALL allow recording salary payments with teacher, amount, month, and payment date
2. THE Salary_Manager SHALL display payment history per teacher
3. THE Salary_Manager SHALL generate salary reports by month and teacher
4. THE Salary_Manager SHALL track payment status (paid/pending)
5. THE Salary_Manager SHALL prevent duplicate payments for the same teacher and month

### Requirement 18: Database Backup

**User Story:** As a super administrator, I want to backup and restore the database, so that I can protect against data loss.

#### Acceptance Criteria

1. THE Admin_Panel SHALL allow initiating database backup with one click
2. THE Admin_Panel SHALL store backups with timestamp and allow downloading
3. THE Admin_Panel SHALL allow restoring from a selected backup file
4. THE Admin_Panel SHALL display backup history with date and size

### Requirement 19: Activity Logging

**User Story:** As a super administrator, I want to view system activity logs, so that I can audit user actions.

#### Acceptance Criteria

1. THE Admin_Panel SHALL log significant user actions (create, update, delete operations)
2. THE Admin_Panel SHALL display activity logs with user, action, timestamp, and affected record
3. THE Admin_Panel SHALL allow filtering logs by user, action type, and date range

### Requirement 20: Data Import

**User Story:** As an administrator, I want to import data from CSV/Excel files, so that I can bulk-add students and other records.

#### Acceptance Criteria

1. THE Admin_Panel SHALL allow uploading CSV/Excel files for student import
2. THE Admin_Panel SHALL validate imported data and display errors before committing
3. THE Admin_Panel SHALL show import preview with row count and detected columns
4. THE Admin_Panel SHALL support mapping file columns to database fields

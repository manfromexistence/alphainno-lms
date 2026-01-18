# Requirements Document

## Introduction

This document specifies requirements for enhancing the student portal with improved exam-taking interfaces and a course enrollment system with payment screenshot workflow. The system will provide students with a streamlined exam experience, prevent unauthorized access to exam editing, implement anti-cheating measures, and enable course purchases through payment screenshot verification.

## Glossary

- **Student_Portal**: The web interface used by students to access courses, take exams, and manage enrollments
- **Exam_System**: The subsystem responsible for delivering and managing student exams
- **Payment_System**: The subsystem that handles course payment verification and enrollment
- **Admin_Panel**: The interface used by administrators and teachers to review payments and manage enrollments
- **Exam_Attempt**: A single instance of a student taking an exam
- **Payment_Screenshot**: An image uploaded by a student showing proof of payment transaction
- **Batch**: A group of students enrolled in a course

## Requirements

### Requirement 1: Student Exam Access Control

**User Story:** As a student, I want to only see the "take exam" option, so that I cannot accidentally view or edit exam content inappropriately.

#### Acceptance Criteria

1. WHEN a student views the exam list, THE Student_Portal SHALL display only "Take Exam" actions for available exams
2. WHEN a student attempts to access exam edit or view URLs directly, THE Exam_System SHALL redirect them to the exam-taking interface
3. THE Student_Portal SHALL hide all administrative exam management options from student views

### Requirement 2: MCQ Exam Interface

**User Story:** As a student, I want to take MCQ exams with a timer and question navigation, so that I can manage my time effectively during the exam.

#### Acceptance Criteria

1. WHEN a student starts an MCQ exam, THE Exam_System SHALL display a countdown timer showing remaining time
2. WHEN the exam timer reaches zero, THE Exam_System SHALL automatically submit the exam attempt
3. WHEN taking an MCQ exam, THE Student_Portal SHALL provide navigation controls to move between questions
4. WHEN a student selects an answer, THE Exam_System SHALL save the selection immediately
5. THE Student_Portal SHALL display progress indicators showing answered and unanswered questions

### Requirement 3: CQ Exam Interface

**User Story:** As a student, I want to answer creative questions with text and screenshots, so that I can provide comprehensive answers.

#### Acceptance Criteria

1. WHEN a student takes a CQ exam, THE Student_Portal SHALL provide a text editor for each question
2. WHEN answering CQ questions, THE Student_Portal SHALL allow screenshot uploads for each answer
3. WHEN a student uploads a screenshot, THE Exam_System SHALL validate the file type and size
4. THE Exam_System SHALL store uploaded screenshots with the exam attempt

### Requirement 4: Live Exam Interface

**User Story:** As a student, I want to participate in live exams with real-time updates, so that I can complete time-sensitive assessments.

#### Acceptance Criteria

1. WHEN a live exam is active, THE Student_Portal SHALL display a real-time exam interface
2. WHEN taking a live exam, THE Exam_System SHALL synchronize student responses in real-time
3. THE Student_Portal SHALL indicate the live status of the exam to students

### Requirement 5: Exam Time Validation

**User Story:** As a student, I want to only access exams during their scheduled time, so that exam integrity is maintained.

#### Acceptance Criteria

1. WHEN a student attempts to start an exam before its start time, THE Exam_System SHALL prevent access and display the start time
2. WHEN a student attempts to start an exam after its end time, THE Exam_System SHALL prevent access and display a message
3. WHEN an exam is in progress and the end time is reached, THE Exam_System SHALL automatically submit all active attempts

### Requirement 6: Anti-Cheating Measures

**User Story:** As an administrator, I want to detect potential cheating behaviors, so that exam integrity is maintained.

#### Acceptance Criteria

1. WHEN a student starts an exam, THE Exam_System SHALL request fullscreen mode
2. WHEN a student exits fullscreen during an exam, THE Exam_System SHALL log the event with timestamp
3. WHEN a student switches browser tabs during an exam, THE Exam_System SHALL log the tab switch event
4. THE Exam_System SHALL store all cheating detection events with the exam attempt

### Requirement 7: Exam Results Display

**User Story:** As a student, I want to see detailed exam results with performance analysis, so that I can understand my strengths and weaknesses.

#### Acceptance Criteria

1. WHEN an exam is graded, THE Student_Portal SHALL display the total score and percentage
2. WHEN viewing exam results, THE Student_Portal SHALL show question-by-question breakdown with correct answers
3. WHEN viewing MCQ results, THE Student_Portal SHALL highlight correct and incorrect answers
4. THE Student_Portal SHALL display performance metrics including time taken and accuracy rate

### Requirement 8: Exam Results PDF Export

**User Story:** As a student, I want to download my exam results as PDF, so that I can keep records for my reference.

#### Acceptance Criteria

1. WHEN viewing exam results, THE Student_Portal SHALL provide a "Download PDF" button
2. WHEN a student clicks download, THE Exam_System SHALL generate a PDF containing the complete exam results
3. THE Exam_System SHALL include student information, exam details, scores, and performance analysis in the PDF

### Requirement 9: Exam Leaderboard

**User Story:** As a student, I want to see exam leaderboards, so that I can compare my performance with peers.

#### Acceptance Criteria

1. WHEN an exam is completed, THE Student_Portal SHALL display a leaderboard showing top performers
2. THE Student_Portal SHALL show student rank, name, score, and completion time on the leaderboard
3. THE Student_Portal SHALL highlight the current student's position on the leaderboard

### Requirement 10: Course Browsing

**User Story:** As a student, I want to browse all available courses, so that I can discover learning opportunities.

#### Acceptance Criteria

1. THE Student_Portal SHALL display a list of all available courses with overview information
2. WHEN viewing courses, THE Student_Portal SHALL show course title, description, instructor, and fee
3. WHEN a student is not enrolled in a course, THE Student_Portal SHALL display a "Buy Course" button
4. WHEN a student is enrolled in a course, THE Student_Portal SHALL hide the "Buy Course" button

### Requirement 11: Payment Method Selection

**User Story:** As a student, I want to select my payment method, so that I can pay using my preferred service.

#### Acceptance Criteria

1. WHEN a student clicks "Buy Course", THE Payment_System SHALL display available payment methods
2. THE Payment_System SHALL support bKash, Nagad, Rocket, and bank transfer options
3. WHEN a student selects a payment method, THE Payment_System SHALL display payment instructions

### Requirement 12: Payment Screenshot Upload

**User Story:** As a student, I want to upload payment proof, so that my enrollment can be verified.

#### Acceptance Criteria

1. WHEN submitting payment, THE Payment_System SHALL require a screenshot upload
2. WHEN a student uploads a screenshot, THE Payment_System SHALL validate the file format
3. THE Payment_System SHALL accept image formats including JPG, PNG, and PDF
4. WHEN upload is successful, THE Payment_System SHALL create a payment record with pending status

### Requirement 13: Payment Review and Approval

**User Story:** As an administrator, I want to review payment screenshots, so that I can verify and approve enrollments.

#### Acceptance Criteria

1. WHEN a payment is submitted, THE Admin_Panel SHALL display it in the pending payments list
2. WHEN reviewing a payment, THE Admin_Panel SHALL show the screenshot, student details, and course information
3. WHEN an admin approves a payment, THE Payment_System SHALL add the student to the course batch
4. WHEN an admin rejects a payment, THE Payment_System SHALL update the status and notify the student

### Requirement 14: Student Payment Dashboard

**User Story:** As a student, I want to track my payments, so that I can monitor my enrollment status.

#### Acceptance Criteria

1. THE Student_Portal SHALL display a payment dashboard showing all payment records
2. WHEN viewing the dashboard, THE Student_Portal SHALL show total course fee for each enrollment
3. THE Student_Portal SHALL display amount deposited, pending payments, and payment history
4. WHEN viewing payment history, THE Student_Portal SHALL show status for each payment
5. THE Payment_System SHALL support payment status values: pending, approved, and rejected

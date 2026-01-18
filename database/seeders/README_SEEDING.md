# Database Seeding Documentation

## Overview
The `ComprehensiveDataSeeder` populates the database with realistic dummy data for all admin panel pages and student/parent portals.

## What Gets Seeded

### 1. **Course Videos** 📹
- **5-8 videos per course** with real educational YouTube content
- Free educational videos from channels like freeCodeCamp, Programming with Mosh
- Topics: Programming, Mathematics, Science, English, and more
- Each video includes title, description, duration, and preview status

### 2. **Class Schedules** 📅
- **4-6 classes per week** for each batch
- Realistic time slots (8 AM - 7 PM)
- Various subjects: Mathematics, English, Physics, Chemistry, Biology, etc.
- Assigned teachers and rooms
- Covers all weekdays (Saturday to Thursday)

### 3. **Attendance Records** 📊
- **60 days of attendance** for each student
- Realistic distribution:
  - 70% Present
  - 10% Absent
  - 15% Late
  - 5% Excused
- Skips weekends (Friday)
- Enables attendance percentage calculations

### 4. **Exams and Results** 📝
- **3-5 exams per batch** (MCQ and Creative Questions)
- Each exam includes:
  - 10 MCQ questions (50 marks) OR 5 CQ questions (100 marks)
  - Realistic duration (45-180 minutes)
  - Pass marks and grading system
- **85% student participation** rate
- Results with realistic grade distribution:
  - 10% Fail (F)
  - 30% Average (D, C)
  - 40% Good (B, A-)
  - 20% Excellent (A, A+)
- Includes feedback for each result

### 5. **Invoices and Payments** 💰
- **2-4 invoices per student**
- Various fee types:
  - Monthly Tuition Fee
  - Admission Fee
  - Exam Fee
  - Course Materials Fee
  - Library and Lab Fee
  - Sports and Activities Fee
- **65% payment completion** rate
- **30% of unpaid invoices** are overdue
- Multiple payment methods: Cash, Bank Transfer, Card, Mobile Banking (bKash, Nagad)
- Unique transaction IDs for each payment

### 6. **Parent Accounts** 👨‍👩‍👧‍👦
- **80% of students** have parent accounts
- **25% sibling relationships** (shared parents)
- Relationship types: Father, Mother, Guardian
- Notification preferences enabled
- Email and phone verification
- Approved parent-student relationships

## How to Run

### Fresh Database Seeding
```bash
# Drop all tables and reseed
php artisan migrate:fresh --seed
```

### Run Only Comprehensive Seeder
```bash
# If you already have basic data (roles, users, courses, batches, students, teachers)
php artisan db:seed --class=ComprehensiveDataSeeder
```

### Run Specific Seeders
```bash
# Course videos only
php artisan db:seed --class=CourseVideoSeeder

# Class schedules only
php artisan db:seed --class=ClassScheduleSeeder

# Parents only
php artisan db:seed --class=ParentSeeder

# Invoices and payments only
php artisan db:seed --class=PaymentInvoiceSeeder

# Exams and results only
php artisan db:seed --class=ExamSeeder
```

## Admin Panel Pages Now Populated

### Student Portal
✅ **My Courses** - Course videos available for all enrolled courses
✅ **My Results** - Exam results with grades and feedback
✅ **Class Schedule** - Weekly timetable with subjects and timings
✅ **My Fees** - Invoices (paid, pending, overdue) and payment history

### Parent Portal
✅ **Children Overview** - List of linked students
✅ **Academic Progress** - Student exam results and grades
✅ **Attendance Records** - 60 days of attendance data with percentages
✅ **Fee Status** - Invoice and payment information for each child

### Admin Dashboard
✅ **Courses** - Courses with video content
✅ **Batches** - Batches with schedules
✅ **Students** - Students with attendance and results
✅ **Teachers** - Teachers assigned to schedules
✅ **Exams** - Exams with questions and results
✅ **Payments** - Invoices and payment records
✅ **Attendance** - Daily attendance tracking
✅ **Reports** - Data for generating reports

## Test Credentials

### Admin
- Email: `admin@example.com`
- Password: `password`

### Students
- Email: `student{N}@example.com` (where N is 1, 2, 3, etc.)
- Password: `password`

### Teachers
- Email: `teacher{N}@example.com`
- Password: `password`

### Parents
- Email: `parent{N}@example.com` (where N is 1, 2, 3, etc.)
- Password: `password`

## Data Statistics (Approximate)

Based on default seeding with 5 courses, 10 batches, 50 students, 5 teachers:

- **Course Videos**: ~300-400 videos
- **Class Schedules**: ~50-60 schedules
- **Attendance Records**: ~3,000 records (50 students × 60 days)
- **Exams**: ~30-50 exams
- **Exam Questions**: ~300-500 questions
- **Exam Results**: ~1,200-2,000 results
- **Invoices**: ~100-200 invoices
- **Payments**: ~65-130 payments
- **Parent Accounts**: ~40 parents
- **Parent-Student Relationships**: ~40-50 relationships

## Notes

1. **YouTube Videos**: All videos are real, free educational content from YouTube. No copyright issues.

2. **Realistic Data**: The seeder uses weighted random distributions to create realistic patterns (e.g., most students pass, most invoices are paid).

3. **Dependencies**: The comprehensive seeder requires basic data to exist first:
   - Roles and Permissions
   - Admin User
   - Courses
   - Batches
   - Students
   - Teachers

4. **Performance**: Seeding large amounts of data may take 1-2 minutes. Be patient!

5. **Idempotency**: Running the seeder multiple times will create duplicate data. Use `migrate:fresh --seed` for a clean slate.

## Troubleshooting

### "No courses found"
Run the basic seeders first:
```bash
php artisan db:seed --class=CourseSeeder
php artisan db:seed --class=BatchSeeder
```

### "No students found"
Run the student seeder:
```bash
php artisan db:seed --class=StudentManagementSeeder
```

### "No teachers found"
Run the teacher seeder:
```bash
php artisan db:seed --class=TeacherManagementSeeder
```

### Slow seeding
This is normal for large datasets. The seeder creates thousands of records with realistic relationships.

## Customization

To modify the seeding behavior, edit `ComprehensiveDataSeeder.php`:

- Change video count: Modify `rand(5, 8)` in `seedCourseVideos()`
- Change attendance days: Modify `for ($i = 60; $i >= 0; $i--)` in `seedAttendanceRecords()`
- Change exam count: Modify `rand(3, 5)` in `seedExamsAndResults()`
- Change invoice count: Modify `rand(2, 4)` in `seedInvoicesAndPayments()`
- Adjust payment rate: Modify `rand(1, 100) <= 65` in `seedInvoicesAndPayments()`

## Support

For issues or questions about seeding, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Database connection in `.env`
3. Migration status: `php artisan migrate:status`

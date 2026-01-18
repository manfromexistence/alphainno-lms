# Quick Start: Database Seeding

## 🚀 One Command to Rule Them All

```bash
php artisan migrate:fresh --seed
```

This single command will:
1. Drop all existing tables
2. Run all migrations
3. Seed all data including:
   - Roles and permissions
   - Admin user
   - Courses and batches
   - Students and teachers
   - **Course videos** (300-400 videos)
   - **Class schedules** (50-60 schedules)
   - **Attendance records** (~3,000 records)
   - **Exams and results** (30-50 exams, 1,200-2,000 results)
   - **Invoices and payments** (100-200 invoices)
   - **Parent accounts** (~40 parents)

## ⏱️ Expected Time
- **Small dataset** (default): 1-2 minutes
- **Large dataset**: 3-5 minutes

## ✅ Verify Everything Worked

```bash
php artisan db:verify-seeded-data
```

This will show you:
- Count of all seeded data
- Any missing data
- Statistics and completion rates

## 🔑 Login Credentials

### Admin Dashboard
```
Email: admin@example.com
Password: password
```

### Student Portal
```
Email: student1@example.com (or student2, student3, etc.)
Password: password
```

### Teacher Portal
```
Email: teacher1@example.com (or teacher2, teacher3, etc.)
Password: password
```

### Parent Portal
```
Email: parent1@example.com (or parent2, parent3, etc.)
Password: password
```

## 📊 What You'll See

### Student Portal
- ✅ **My Courses**: 5-8 educational videos per course
- ✅ **My Results**: Exam results with grades (A+ to F)
- ✅ **Class Schedule**: Weekly timetable
- ✅ **My Fees**: Invoices and payment history

### Parent Portal
- ✅ **Children Overview**: Linked students
- ✅ **Academic Progress**: Exam results and grades
- ✅ **Attendance Records**: 60 days of attendance
- ✅ **Fee Status**: Invoice and payment details

### Admin Dashboard
- ✅ All pages populated with realistic data
- ✅ Reports can be generated
- ✅ Charts and graphs will display data

## 🎯 Common Commands

### Fresh start (recommended)
```bash
php artisan migrate:fresh --seed
```

### Add data to existing database
```bash
php artisan db:seed --class=ComprehensiveDataSeeder
```

### Check what's in the database
```bash
php artisan db:verify-seeded-data
```

### Run specific seeders
```bash
# Only course videos
php artisan db:seed --class=CourseVideoSeeder

# Only class schedules
php artisan db:seed --class=ClassScheduleSeeder

# Only parents
php artisan db:seed --class=ParentSeeder
```

## 🐛 Troubleshooting

### "SQLSTATE[HY000] [2002] Connection refused"
**Problem**: Database not running
**Solution**: Start your database server (MySQL/PostgreSQL)

### "Class 'ComprehensiveDataSeeder' not found"
**Problem**: Composer autoload not updated
**Solution**: Run `composer dump-autoload`

### Seeding takes too long
**Problem**: Large dataset
**Solution**: This is normal. Wait for completion or reduce data volume in seeder

### Duplicate data
**Problem**: Ran seeder multiple times
**Solution**: Use `migrate:fresh --seed` to start clean

## 📚 More Information

- **Full Documentation**: See `database/seeders/README_SEEDING.md`
- **Summary**: See `SEEDING_SUMMARY.md`
- **Source Code**: See `database/seeders/ComprehensiveDataSeeder.php`

## 💡 Pro Tips

1. **Always use `migrate:fresh --seed`** for a clean database
2. **Run `db:verify-seeded-data`** after seeding to confirm
3. **Test with different user roles** (admin, student, teacher, parent)
4. **Check all admin panel pages** to see the data
5. **Customize the seeder** if you need different data volumes

---

**Ready to go?** Run this now:
```bash
php artisan migrate:fresh --seed && php artisan db:verify-seeded-data
```

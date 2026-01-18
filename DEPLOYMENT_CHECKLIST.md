# Deployment Checklist - Database Compatibility Fixes

## Pre-Deployment

### 1. Code Review
- [x] All files modified and tested
- [x] No syntax errors (getDiagnostics passed)
- [x] No remaining `whereYear()` or `whereMonth()` calls
- [x] Documentation created

### 2. Local Testing (SQLite)
```bash
# Run tests
php artisan test --filter DatabaseCompatibilityTest

# Start local server
php artisan serve

# Test manually:
# - Visit http://localhost:8000/dashboard
# - Check all admin panel pages
# - Verify reports generate correctly
```

### 3. Backup Production Database
```bash
# Before deploying, backup your MySQL database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

## Deployment Steps

### 1. Commit Changes
```bash
git add .
git commit -m "Fix database compatibility issues between SQLite and MySQL

- Replace whereYear/whereMonth with whereBetween
- Fix date filtering in all services and controllers
- Add comprehensive test suite
- Update documentation

Fixes #[issue-number] - 500 errors on admin dashboard in production"
git push origin main
```

### 2. Deploy to Production
```bash
# Pull latest changes on production server
git pull origin main

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Run Migrations (if any)
```bash
# Check if any migrations need to run
php artisan migrate:status

# Run migrations if needed
php artisan migrate --force
```

## Post-Deployment Testing

### 1. Critical Path Testing
- [ ] Visit `/dashboard` - should load without 500 errors
- [ ] Check dashboard statistics display correctly
- [ ] Verify all charts render with data
- [ ] Test date filtering in reports

### 2. Admin Panel Testing
- [ ] **Payments**
  - [ ] View payments list
  - [ ] Filter by month
  - [ ] Create new payment
  - [ ] Verify receipt number generates correctly
  
- [ ] **Salaries**
  - [ ] View salaries list
  - [ ] Filter by month
  - [ ] View salary report
  - [ ] Create new salary payment
  
- [ ] **Reports**
  - [ ] Generate attendance report
  - [ ] Generate payment report
  - [ ] Generate student report
  - [ ] Export reports to PDF/Excel

- [ ] **Students**
  - [ ] View students list
  - [ ] Filter by enrollment year
  - [ ] View student details
  - [ ] Check attendance percentage

### 3. Performance Testing
- [ ] Dashboard loads in < 2 seconds
- [ ] Reports generate in < 5 seconds
- [ ] No N+1 query issues
- [ ] Check query performance in logs

### 4. Error Monitoring
```bash
# Check Laravel logs for errors
tail -f storage/logs/laravel.log

# Check for database errors
grep -i "database\|query\|sql" storage/logs/laravel.log | tail -20
```

## Rollback Plan (If Issues Occur)

### 1. Quick Rollback
```bash
# Revert to previous commit
git revert HEAD
git push origin main

# Or checkout previous version
git checkout [previous-commit-hash]
git push origin main --force

# Clear caches
php artisan cache:clear
php artisan config:clear
```

### 2. Restore Database (if needed)
```bash
# Restore from backup
mysql -u username -p database_name < backup_YYYYMMDD_HHMMSS.sql
```

## Success Criteria

✅ All checklist items completed  
✅ No 500 errors in production  
✅ All admin panel pages load correctly  
✅ Reports generate successfully  
✅ Date filtering works as expected  
✅ No errors in Laravel logs  
✅ Performance is acceptable  

## Monitoring (First 24 Hours)

- [ ] Check error logs every 2 hours
- [ ] Monitor user reports/complaints
- [ ] Verify data accuracy in reports
- [ ] Check database query performance
- [ ] Monitor server resources (CPU, memory)

## Communication

### Before Deployment
```
Subject: Scheduled Maintenance - Database Compatibility Fixes

We will be deploying database compatibility fixes to resolve 500 errors 
on the admin dashboard. Expected downtime: 5-10 minutes.

Deployment window: [DATE] [TIME]
```

### After Deployment
```
Subject: Deployment Complete - Database Compatibility Fixes

The database compatibility fixes have been successfully deployed. 
All admin panel pages should now load without errors.

If you encounter any issues, please report them immediately.
```

## Notes

- All changes are backward compatible
- No database schema changes required
- No data migration needed
- Can be rolled back safely if issues occur

---

**Prepared by**: Kiro AI  
**Date**: January 19, 2026  
**Estimated Deployment Time**: 10-15 minutes  
**Risk Level**: Low (no schema changes, backward compatible)

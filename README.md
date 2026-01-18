# Alphainno LMS

This is the official repository for the Alphainno Learning Management System (LMS). This project is designed to provide a comprehensive platform for managing educational courses, tracking student progress, and facilitating communication between educators and learners.

## Zip with windows 7-Zip

```bash
npm run build && "/c/Program Files/7-Zip/7z.exe" a -tzip ../lms-updated-again.zip . '-xr!node_modules' '-xr!.git' -mx=1
```

### Cpanel Deploy

```bash
php artisan key:generate && php artisan migrate:fresh --seed && rm -rf public/storage && php artisan storage:link
```

Please add some data for these admin panel pages and put them in the main seeder correctly!!!
Accounts
Overview
Income Management
Expense Tracking
Financial Reports

Inventory
Items List
Inventory Report

Reports

Communication
Send SMS
SMS Templates
SMS Logs
Announcements

System Admin
Database Backup
Activity Logs
Bulk Import


Please update the students frontend pages class student cards section to have border or shadow correctly

And now many admin panel pages like the Inventody sidebar pages and other may not use our custom data-table properly or dont't use at all so please check for all admin panel pages where we use 
# Alphainno Lms

This is the official repository for the Alphainno Learning Management System (LMS). This project is designed to provide a comprehensive platform for managing educational courses, tracking student progress, and facilitating communication between educators and learners.

## Zip with windows 7-Zip

```bash
npm run build && "/c/Program Files/7-Zip/7z.exe" a -tzip ../lms.zip . '-xr!node_modules' '-xr!.git' -mx=1
```

### Cpanle Deploy

```bash
rm -f database/migrations/2026_01_13_100000_add_performance_indexes_to_products_table.php
rm -f database/migrations/2026_01_13_100001_add_performance_indexes_to_orders_table.php
rm -f database/migrations/2026_01_13_100002_add_performance_indexes_to_order_items_table.php
rm -f database/migrations/2026_01_13_100003_add_performance_indexes_to_categories_table.php
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/*.php

php artisan key:generate && php artisan migrate:fresh --seed && rm -rf public/storage && php artisan storage:link
```

Please study the Project folder - as its a laravel blade project - in that project there is seeder and data for many admin panel pages - but still lack seeder for for many admin panel pages too - so please study the project and add some dummy data in seeder not a php file to populate the database - just update the actual seeder to have some data about all tables in the project - Like the courese tables currently don't have any videos data so please put some free educational videos for all coures and add some data for these admin panel pages and look for more tables with no data and add some in them:
My Courses
My Results
Class Schedule
My Fees
Children Overview
Academic Progress
Attendance Records
Fee Status

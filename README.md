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

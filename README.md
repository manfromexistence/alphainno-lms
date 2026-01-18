# Alphainno LMS

This is the official repository for the Alphainno Learning Management System (LMS). This project is designed to provide a comprehensive platform for managing educational courses, tracking student progress, and facilitating communication between educators and learners.

## Zip with windows 7-Zip

```bash
npm run build && "/c/Program Files/7-Zip/7z.exe" a -tzip ../lms.zip . '-xr!node_modules' '-xr!.git' -mx=1
```

### Cpanel Deploy

```bash
php artisan key:generate && php artisan migrate:fresh --seed && rm -rf public/storage && php artisan storage:link
```

Now, I have logged in as parent and as you can see that the parent dashboard is empty so please our custom data table, select from resources/views/components/ui and other components and show nice charts dashboard for parents

Good, now when we logged in a parent - and for parents we have dashboard.children not dashboard page so please remove the children dashbaord item the sidebar so it will look like its parent dashboard and when we are logged in a parent and click on dashbaord please hightlight dashboard item in the sidebar!!!

As a student when I am goint to:
MCQ Exam
CQ Exam
Live Exam
Exam Leaderboard
pages I am seeing view and edit exam - but students should only be able to take exam with good LMS like exam taker ui with screenshot upload for CQ and timer for MCQ and also with exam start time and end time validation and also exam result page with detailed analysis of exam performance and also with option to download pdf of exam result. And also no cheating support and so on - and students can see all coures overview no actualy coures but the coures buy button which they have to buy using our payment method - which is paying with bkash, nagad, rocket and so on and the student will show the screenshot of the transaction and then the admin and teacher can aprrove that student of buying that coures, addint to batch or class and so on and also show students payment status about how much they have deposited and so on!!!

we already have most of the task complete - just mostly need to connects things so please remove all tests from the spec and make the spec in a way that its faster to add main functionalities correctly!!!

Please learn from .kiro/specs/students-port-enhancement and make sure to do these as fast as possible!!!

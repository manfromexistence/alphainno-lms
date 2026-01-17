# Alphainno Lms

This is the official repository for the Alphainno Learning Management System (LMS). This project is designed to provide a comprehensive platform for managing educational courses, tracking student progress, and facilitating communication between educators and learners.

## Zip with windows 7-Zip

```bash
npm run build && "/c/Program Files/7-Zip/7z.exe" a -tzip ../chapakhana-updated.zip . '-xr!node_modules' '-xr!.git' -mx=1
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

@index.blade.phpAt admin panel as you can see that current we are using dummy links - so please create a tasklist to create actual pages in there!!! And make sure to use reuseable components instead of just creating nwe components - make components like shadcn-ui, simple and put the components in components/ui folder and use them with our blade setup and also first please create these 3 components:
1. textInput: A admin panel text input component which uses blade, tailwindcss and javascript to show seeded data and then show live character counter and also has min max characters with other validations
2. imageInput: An image input component which shows current seeded image and then gives image file input when hovering or cliking on the image and in the bottom it will show url of the image and we can also put a image url and it will load that image and when using file input image it will still show the url of the image in bottom correctly!
3. videInput: it will be like the image input but for videos!
So, like this we will use these reuseable components instead of creating these again and again so please create them correctly!!!

Please study this codebase - mainly the admin panel layout and make sure that we created all of the admin panel pages at admin panel sidebar and routes correctly as all the admin panel sidebar item!!!




Good, now need to start working to make all admin panel pages functional as even we now have all admin panel pages but still they are just dummy ui - so we have to make the functional!!!
Start with student pages - So, please study this codebase and create controllers and amdmin panels ui to make admin panel studnets management functional - So, please update pages "http://127.0.0.1:8000/dashboard/students/create" like this work correctly and create a plan to make admin student management functional and also make sure it should be connected with frontend students correctly!!!

As you can see in our admin panel teacher pages - but we don't have any seeded data in those admin panel sidebar teacher sub-menu pages so pleas create seeded data for all admin panel teacher sub-menu pages and use unslash images!!!

Awesome, now please start working to make all admin panel teacher sub-menu pages functional as even we now have all admin panel teacher sub-menu pages but still they are just dummy ui - so we have to make the functional!!!

Good, now please study this screenshot - I gave you and add all student feilds listed in the screenshot and update our controllers and student admin panel pages with the new feilds!!!


Awesome, now please start working to make all admin panel course and classes sub-menu pages functional as even we now have all admin panel course and classes sub-menu pages but still they are just dummy ui - so we have to make the functional!!!

Good, now please study this screenshot - I gave you and add all student feilds listed in the screenshot and update our controllers and student admin panel pages with the new feilds!!!


You did readlly well - now do these:
1. Remove the signature from all controllers and admin panels use this Payment (Total Amount, Paid Amount, Due Amount, Payment Method) instead of Signature
2. And in admin panel - you mostly used text input, but we have to mostly use select instead of text input = like for bloud group use select to list all blood groups and use our select component and for for religion use select to list all religions and use our select component 
3. From course information please remove 
Class Days
Class Time
4. Id no should be auto generated and will be unique and the super admin will decide what format the id will be like
5. At Course Information: So, this feilds in the ui will be connected with each other like if you select a course then the batches related to that course will be shown in the batch select input - And this goes the other way around as well like if you select a batch and time details then the courses related to that batch will be shown in the course select input!!!

Please study the student controller and update it to have both class, course and batch instead of just course and batch and make the class 1 to 12 and in the student create admin panel page make sure to put the class dropdown and make that create section connected with if one thing is changed then the other things should be updated as well!!! And make sure to update all student admin panel pages with the new feilds!!!

At the resources/views/components/ui folder please create a component called carosel like shadcn-ui carosel input and there we will use a reuselable carosel component using javascript so that we can use in our website any time we need a carosel input!!! Make the carosel like shadcn-ui carosel correctly!!!

On my windows os - when I uninstall an app - the remaing still stays in my pc and waste my pc storage so - is there any windows app to uninstall app with all of the apps cahces correctly??

So, this is laravel blade lms project but in this project the design system is very messsy and bad so please create a shadcn-ui like design system create laravel blade version of all shadcn-ui compnents and put them in the resources/views/components/ui folder!!! And also create shadcn-ui like css variables like primary and others but don't use in the frontend pages as we will work on that later just make sure to update our tailwindcss with our shadcn-ui like design system and components in laravel blade and I will manually use that but don't update any frotend ui for now!!!

Good, now as you can see that the admin panel has so many pages but those are mostly dummy ui - and this is a lms project with teachers, students, courses, batches, classes, subjects, exam, attendance, fees, etc and also there is online and offline support - plus the admin panel will show items based on the logged in user role as if its a students then it will only show students related pages and if its a teacher then it will only show teacher related pages and if its a parent then it will only show parent related pages and if its a super admin then it will show all pages - Also you can add student based on year and file them and when a new student will be registered the super admin can decide what will be the id format of the student - Plus The student can decide what course and stuffs to show to the students dashboard and also the super admin can decide what course and stuffs to show to the students dashboard - so please create all admin panel pages functional and connected with each other by creating a new spec about it!!!

Please study this codebase and make sure that all admin panel sidebar items exits in our website and make sure to update all admin panel pages with new reuseable components from the resources/views/components/ui folder!!!

Good, now please creating all tests complete the lms-admin-panel spec as fast as possible without any tests - we can make tests after we completed everything - Now, please look at the "http://127.0.0.1:8000/dashboard/students/create" admin route  as first its not storing the image and Academic Information selects option correctly and when I am clikcking on "Register Student" button then its showing nothing or any errors and just reloading the forms - if there is error then we must show what's the error!!!

In the admin panel sidebar please create two icon buttons:
1. collapse all sidebar items
2. expand all sidebar items
And put them in the top right corner of the admin panel sidebar and make them functional!!!

Make sure to not change frontend ui - just make the text and image of the frontend dynamic from the admin panel!!!
Awesome, now you can see that there are frotnend hardcoded dummy pages - all those pages will me like CMS and dynamic so that from the admin panel we can change the frotnend pages - so please create those CMS like pages and allow only superadmins to see thoes pages!!!
And do it as fast as possible without any tests - we can make tests after we completed everything - And please our resources/views/components/ui folder is our ui component folder - so use those components to make the admin panel pages functional!!!

In this codebase at students admin panel and in all other pages our reuseable image-input is not handling file image input correctly its works correctly in the url image input but its not working when using file image input as its just show abtract error - so please update php ini of this project to accept max 20 MB images and make  sure that its handles file inputs files correctly!!!

At this codebase the teachers and students admin panel pages are completed but coures, batches and other admin panel pages - so please make those other admin panel pages functional and connected with each other correctly!!!

In this codebase when trying to delete something - we are using browser native alert for confirmation - so please create a custom dialogue to confirm the deletion!!! And make sure to update all delete buttons with the new custom dialogue!!! And put that dialogue components to resources/views/components/ui folder!!! Also study this codebase and list what browser native stuffs we still using as we need to create custom components for all of them!!!

Good, now in admin panel at batch, courses - we have dummy ui but still they are not funtional so please fix them and also in admin panel please sidebar please add class menu with items with 1 to 12 class and this courses, batch and class will be connected with each and all will be functional and also seed them with some data - don't create any spec and just do it!!!

At admin panel "/dashboard/setting" page please fix this error:
```markdown

```

Currently at admin panel payment, report and communication is just dummy ui - so please use make them functional - we don't have any real payment or sms system so just create the placeholder for payment and sms but use free ways to test them for now - like for payment please use cash by bkask, nagad and other and there we will show our phone number and an instruction for users to pay for now for reports please create real download and convert to excel and other formats from our reusebale data table and make the whole report functional with our admin panel data correctly!!!

notesofshahriarold/public = zed

Practise Makes A Person Perfect

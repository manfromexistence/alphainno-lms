Check is a formatter and linter for all languages and it also covers most pupular frameworks and tools of all the programming langues - this is big tasks and kinda like impossible but check will do this by support all prettier rules as unlike other formatter and linter like biome, prettier and eslint check usese dx-serilaizer where it can add new rules of formatter and linter just by adding a line in the javascript-linter.sr file so even people with little to no coding experience can contribute to check formatter and linter - check supports javascript and typescript it also supports reactjs, nextjs, svelet and other frameworks too out of the box!

Check folder also lists all tests, calculates security in the codebase and checks if this a production ready codebase or not and gives 1 to 100 scores - its written in rust and you can use it using dx cli and dx code editor extension - where its list all linting and formatting problems in one tab at extension and in the bottom accordio its show security and current score (1 to 100) and lists why its that score plus in this accordion you can import design patters and best practices pluggins to follow those while search for security flaws - its a plugin that will be stored in dx website! and at dx extension check tab's bottom there is test accordion where its lists all tests and their status - In here that dx extension check tab you will copy all problems, security problems and button to fix that with ai based on the respective ai code editor and test runner button so you can run tests without wasting tokens in a beatiful way!

In order for check to provide all these check needs to have all the linter and formatter for all languages and their most popular frameworks and have security scanner and 

```markdown
In our admin panel please do these two tasks
1. make all teacher related admin panel page work correctly as "http://127.0.0.1:8000/dashboard/teachers/assignments" at this route the edit and delete button is doing nothing so please add that funtionality and then please look for these types of missing functionality and add it througth out the admin panel
2. At courese there are is no way to add videos so please add that functionality when a student buys a course only then he should be able to see those videos and also superadmin, admin, teacher can see and edit those videos all time!!!

Please seed some data in these:
1. Exams
2. Inventory
3. Reports
4. Accounts
5. Check for any other admin panel pages which current don't have any data and seed them so we can test all the features of lms as soon as possible correctly

At course please seed some youtube videos and also make sure that you upload video files with storing that only seen to a student when the buy that course and also make sure that super-admin, admin, teachers can edit and create those videos in course correctly
```

And in the exam if its a online exam then we will check if the student is doing cheating or not by checking if the student has opened another tab or not by using browser api and if yes then we will automatically submit that exam and mark it as cheating! And make sure to have a good ui so that exams works correclty and also there are offline exam support where student will able to send screenshots of the offline papers and its will be stored as their answers and then teahcers can views those answers and give results in their answers - so please add pen tools using a javascript library so that teachers can check students answers in offline paper screenshots and when an exam is done then all the answers by the students will be showend to teachers for the review and scoring and then the results will come back to students corrrectly - please make all these features and admin panel pages!!!

At "http://127.0.0.1:8000/dashboard/teachers/6/assignment/edit:" Subject Assignments is dummy ui and not interactive so please fix it!

Good, also put features to take questions from excel shets, csv and other data formats!!! And put features to export all the date to excel sheets, csv, json and other data formats

And when I am clicking on the questions edit and delete icon its showing failed to laod quesiton!!!

Please use our data table component for all the tables in exam "Student Results" sections and put view, edit and delete result button and make sure that the works correctly

Now, please create these admin panel pages:
http://127.0.0.1:8000/dashboard/cq-exams
http://127.0.0.1:8000/dashboard/live-exams

At result and leaderboard - the teachers should be to see all the answers by the students and can give score and reuslt the students using pen tools and also the teachers should be able to see the offline exam papers and mark them using pen tools and also the teachers should be able to see the online exam papers and see the student answers and also see if the student has opened another tab or not by using browser api and if yes then we will automatically submit that exam and mark it as cheating! And then there should be send sms to students so that the result to the students correctly by their phone numbers
at these page "http://127.0.0.1:8000/dashboard/exam-results"
And this "http://127.0.0.1:8000/dashboard/exam-leaderboard" we can see all exam scores and student performance by their results in our custom data table!!!


In our custom data table compoennts if the table gets more space then the viewport then its will hidden so please make sure to have a touch drag in mobile and mouse drage features in pc so that we can move the data table like a carosel!!!

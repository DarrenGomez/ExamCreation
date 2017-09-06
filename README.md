# ExamCreation


This was a project done in my Design in Software Engineering class at NJIT completed between myself and 2 fellow students. I worked on and created the backend of the project which was the SQL database queries and commands for the other members of the group to use.

This was completed in PHP using the mySQLi for all sql queries.

In order for my code to communicate with the middle and front end, we sent our information back and forth through json_encode.
Between the 3 of us, we had mapped out ahead of time which database actions would require which post variables. For example, if you look at my code, for the "login" action, I am looking for the POST variables of $user and $pass. 

# Objective of the Assignment
The goal of this project was to create a tool that would allow a professor to create an coding exam for his class by choosing some precreated exam questions. The students would then take the exam online and their answers would be automatically graded and available for the professor to review. Upon review, the professor would then allow the students' scores to be displayed back to them.

Each question is in java and has a correct output that the student's will be compared against. 

# Functionality
1.) There is a login page redirecting to two different pages, one for professors and one for students.

2.) The professor is able to "create" an exam by choosing from different predefined questions.

3.) The professor is able to add new questions to the database of predefined questions.

4.) Students are able to log in and take any available exams.

5.) Their exam answers are graded against the predefined correct output for each question.

6.) The exam results are then available to the professor to review.

7.) The professor then releases the score for the student to see.

# My Contribution
I was the backend for this assignment thus I was responsible for all of the SQL queries and the management of the SQL databases.

Specifically, I created different functions to do the following:

1.) Check for the login information from a SQL database. Because this was for a class, there was no need to salt the password or create any other security functionality. I understand the process of password salting and would be confident in applying this in a real world setting.

2.) Adding a question to the question bank of predefined exam questions. 

3.) Getting a single question from the question bank by its ID or retrieving the entire question bank for display purposes.

4.) Adding an exam to the appropriate SQL database, storing the selected questions under this exam.

5.) Saving a student's response to an exam question and storing it for grading later on. After grading, also saving his score and being able to retrieve his response and his score for display or grading purposes.


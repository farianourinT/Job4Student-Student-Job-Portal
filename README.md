
📌 Project Title

Job4Student – Student Job Portal 

📖 Project Description

Job4Student is a web-based job portal designed to connect students with recruiters through a simple, secure, and user-friendly platform. The system allows recruiters to post jobs and manage applications, while students can browse jobs, maintain profiles, and apply to jobs without page reload using AJAX.

💡 Motivation

The motivation behind this project was to build a real-world academic system that demonstrates core web development concepts such as MVC architecture, authentication, database integration, and asynchronous communication — without relying on UI frameworks like Bootstrap.

🎯 Why This Project Was Built

To practice clean separation of concerns (MVC)

To implement AJAX/JSON in a meaningful feature

To simulate a real job portal workflow for students and recruiters

To meet faculty requirements for a full-stack web project

🧩 Problem It Solves

Eliminates page reload during job application using AJAX

Provides role-based access (Student / Recruiter / Admin)

Centralizes job postings and applications in a structured system

Ensures secure data handling with validation and prepared statements

📚 What We Learned

How to design and refactor a project into MVC

Using AJAX (Fetch API) with PHP + JSON

Secure file uploads (CV, profile image)

Role-based authentication with sessions

Writing maintainable and readable PHP code

🌟 What Makes This Project Stand Out

✔ No Bootstrap — pure HTML & CSS

✔ AJAX-based job application (no reload)

✔ Partial MVC refactor with Models, Controllers, Views

✔ CV upload per job application

✔ Faculty-friendly, clean architecture

🧭 Table of Contents

Project Description

Features

Technologies Used

Installation & Setup

How to Use

Project Structure (MVC)

Security Considerations

AJAX Implementation

Contribution Guidelines

License

✨ Features
👨‍🎓 Student

Register & login

Create and update profile

Upload profile CV and image

Browse jobs

Apply to jobs without page reload (AJAX)

Upload a separate CV per job

🧑‍💼 Recruiter

Post jobs

View applications

Manage job listings

Maintain company profile

🛡 Admin

Manage users

Manage job posts

Review applications

🛠 Technologies Used

Frontend: HTML5, CSS3 (Custom, No Bootstrap), JavaScript

Backend: PHP (MVC pattern)

Database: MySQL

AJAX: JavaScript Fetch API + JSON

Authentication: PHP Sessions

Icons: Font Awesome

⚙️ How to Install and Run the Project
Requirements

XAMPP / WAMP / LAMP

PHP 8+

MySQL

Browser (Chrome recommended)

Installation Steps

Clone or download the repository

git clone https://github.com/your-username/Job4Student.git


Move the project to your server directory

htdocs/student_portal


Create a database named:

studentportal


Import the database schema:

database/schema.sql


Configure database credentials:

config/database.php


Run the project:

http://localhost/student_portal

🚀 How to Use the Project
Student Login

Register as a student

Complete profile

Browse jobs

Click Apply → application submits via AJAX

Recruiter Login

Register as recruiter

Add company info

Post jobs

Review applications

Demo Credentials (optional)
Student:
email: student@test.com
password: 123456

Recruiter:
email: recruiter@test.com
password: 123456

🧱 Project Structure (MVC)
student_portal/
│
├── app/
│   ├── controllers/
│   │   ├── JobController.php
│   │   ├── ApplyController.php
│   │
│   ├── models/
│   │   ├── UserModel.php
│   │   ├── JobModel.php
│   │   └── ApplicationModel.php
│   │
│   ├── views/
│   │   ├── layouts/
│   │   ├── pages/
│
├── assets/
│   ├── css/
│   └── js/
│
├── config/
├── database/
├── includes/
└── uploads/

🔐 Security Considerations

Password hashing (password_hash, password_verify)

Prepared statements (mysqli->prepare)

Server-side validation

Role-based access control

File upload validation (type & size)

Session-based authentication

🔄 AJAX / JSON Implementation

Feature: Apply to Job without page reload

JS: Fetch API with FormData

Backend: JSON responses from PHP Controller

UX: Instant success/error message + redirect

fetch('apply_job.php?ajax=1', {
  method: 'POST',
  body: new FormData(form)
})

🤝 How to Contribute

Fork the repository

Create a feature branch

Commit your changes

Push to GitHub

Open a Pull Request

Each team member must commit and push their own work as per project requirements.

📜 License

This project is licensed under the GPL License.
You are free to modify and use this project for educational and commercial purposes.

Learn more: https://choosealicense.com/licenses/gpl-3.0/

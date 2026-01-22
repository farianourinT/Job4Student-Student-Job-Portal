
# Job4Student 🚀

*A Student Job Portal built with PHP, MVC architecture, and AJAX*

![PHP](https://img.shields.io/badge/PHP-8%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![AJAX](https://img.shields.io/badge/AJAX-Fetch%20API-green)
![MVC](https://img.shields.io/badge/Architecture-MVC-success)
![License](https://img.shields.io/badge/License-GPL-blue)

---

## 📌 About the Project

**Job4Student** is a web-based job portal designed to connect **students** and **recruiters** through a secure, role-based system.
The project was built as an **academic full-stack web application**, focusing on clean architecture, security, and modern interaction patterns.

The application demonstrates:

* MVC separation
* AJAX/JSON communication
* Session-based authentication
* Secure database operations
* Custom UI without Bootstrap

---

## 🎯 Motivation

This project was built to:

* Practice **real-world PHP MVC architecture**
* Implement **AJAX without page reload**
* Avoid UI frameworks and rely on **pure HTML/CSS**
* Meet university requirements for:

  * Security
  * Authentication
  * Database integration
  * Git collaboration
  * Feature completeness

---

## ✨ Key Features

### 👨‍🎓 Student

* Register & login
* Create/update profile
* Upload profile CV & image
* Browse jobs
* **Apply to jobs using AJAX (no reload)**
* Upload **job-specific CV**

### 🧑‍💼 Recruiter

* Register & login
* Post jobs
* Manage job listings
* View student applications
* Maintain company profile

### 🛡 Admin

* Manage users
* Manage jobs
* Review applications

---

## 🧠 What Makes This Project Stand Out

* ✅ No Bootstrap (pure HTML + CSS)
* ✅ MVC architecture (Models / Controllers / Views)
* ✅ AJAX + JSON implemented properly
* ✅ Role-based access control
* ✅ Secure file uploads
* ✅ Faculty-friendly & production-style code

---

## 🛠 Tech Stack

* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** PHP 8+
* **Database:** MySQL
* **Architecture:** MVC
* **AJAX:** Fetch API + JSON
* **Authentication:** PHP Sessions
* **Icons:** Font Awesome

---

## 🗂 Project Structure

```text
student_portal/
│
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
│       ├── layouts/
│       └── pages/
│
├── assets/
│   ├── css/
│   └── js/
│
├── config/
├── database/
├── includes/
├── uploads/
└── index.php
```

---

## ⚙️ Installation & Setup

### Requirements

* PHP 8+
* MySQL
* Apache (XAMPP / WAMP / LAMP)

### Steps

1. Clone the repository:

```bash
git clone https://github.com/your-username/Job4Student.git
```

2. Move to server directory:

```text
htdocs/student_portal
```

3. Create database:

```sql
CREATE DATABASE studentportal;
```

4. Import schema:

```text
database/schema.sql
```

5. Configure DB credentials:

```text
config/database.php
```

6. Run:

```text
http://localhost/student_portal
```

---

## 🚀 Usage

### Student Flow

1. Register/Login
2. Complete profile
3. Browse jobs
4. Click **Apply**
5. Application submits via **AJAX (no reload)**

### Recruiter Flow

1. Register/Login
2. Add company info
3. Post jobs
4. Review applications

---


---

## 🔐 Security

* Password hashing (`password_hash`)
* Prepared statements (`mysqli->prepare`)
* Session-based authentication
* Role-based access control
* Server-side validation
* File upload validation (size & type)

---



## 📜 License

This project is licensed under the **GPL License**.
Free to modify and use for educational or commercial purposes.

🔗 [https://choosealicense.com/licenses/gpl-3.0/](https://choosealicense.com/licenses/gpl-3.0/)

---

## 📌 Final Note

This project fulfills:

* UI (HTML/CSS)
* MVC architecture
* Basic Web Security
* Auth (Session)
* Database integration
* JS validation
* PHP validation
* AJAX/JSON
* Feature completeness



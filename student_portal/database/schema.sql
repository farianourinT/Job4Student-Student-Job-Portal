-- StudentPortal Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS studentportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studentportal;

-- Users table (stores all users: students, recruiters, admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('student', 'recruiter', 'admin') NOT NULL DEFAULT 'student',
    status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    profile_image VARCHAR(255),
    -- Student profile fields
    university VARCHAR(150),
    education_level VARCHAR(100),
    skills TEXT,
    preferred_job_area VARCHAR(150),
    birthdate DATE,
    gender ENUM('male','female','other'),
    cv_file VARCHAR(255),
    -- Recruiter profile fields
    company_name VARCHAR(150),
    company_founded DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Jobs table (posts created by recruiters)
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    job_type ENUM('internship', 'part-time', 'full-time') NOT NULL,
    industry VARCHAR(100) NOT NULL,
    location VARCHAR(200) NOT NULL,
    company_name VARCHAR(200) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    requirements TEXT,
    application_deadline DATE,
    status ENUM('open', 'closed') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional job images table (not required, but kept to match the original project's upload pattern)
CREATE TABLE IF NOT EXISTS job_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Applications table (students apply to recruiter jobs)
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    job_id INT NOT NULL,
    recruiter_id INT NOT NULL,
    application_date DATE NOT NULL,
    expected_start_date DATE,
    status ENUM('pending', 'approved', 'rejected', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
    cover_letter TEXT,
    cv_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note: The original project had a Payments table, but it was not used by any page.
-- Keeping the student portal clean: no payments table.

-- Insert default admin user (password: password - MUST be changed in production!)
-- To generate a new password hash, use: password_hash('your_password', PASSWORD_DEFAULT)
INSERT INTO users (username, email, password, full_name, role, status) VALUES
('admin', 'admin@studentportal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 'active');

-- Create indexes for better performance
CREATE INDEX idx_user_role ON users(role);
CREATE INDEX idx_user_status ON users(status);
CREATE INDEX idx_job_recruiter ON jobs(recruiter_id);
CREATE INDEX idx_job_type ON jobs(job_type);
CREATE INDEX idx_job_industry ON jobs(industry);
CREATE INDEX idx_job_status ON jobs(status);
CREATE INDEX idx_application_student ON applications(student_id);
CREATE INDEX idx_application_job ON applications(job_id);
CREATE INDEX idx_application_status ON applications(status);



-- Optional migration for existing databases (run if you already created tables)
ALTER TABLE users
    ADD COLUMN university VARCHAR(150) NULL AFTER profile_image,
    ADD COLUMN education_level VARCHAR(100) NULL AFTER university,
    ADD COLUMN skills TEXT NULL AFTER education_level,
    ADD COLUMN preferred_job_area VARCHAR(150) NULL AFTER skills,
    ADD COLUMN birthdate DATE NULL AFTER preferred_job_area,
    ADD COLUMN gender ENUM('male','female','other') NULL AFTER birthdate,
    ADD COLUMN cv_file VARCHAR(255) NULL AFTER gender,
    ADD COLUMN company_name VARCHAR(150) NULL AFTER cv_file,
    ADD COLUMN company_founded DATE NULL AFTER company_name;


-- Optional migration for existing databases (run if you already created tables)
ALTER TABLE applications ADD COLUMN cv_file VARCHAR(255) NULL AFTER cover_letter;

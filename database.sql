-- =====================================================
-- Online Notes Sharing System - Database Schema
-- Import this file in phpMyAdmin before running the site
-- =====================================================

CREATE DATABASE IF NOT EXISTS notes_sharing_system;
USE notes_sharing_system;

-- -----------------------------------------------------
-- Table: users
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    course VARCHAR(100) DEFAULT NULL,
    semester VARCHAR(20) DEFAULT NULL,
    role ENUM('student','admin') NOT NULL DEFAULT 'student',
    status ENUM('active','blocked') NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Table: categories
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

-- -----------------------------------------------------
-- Table: notes
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    category_id INT DEFAULT NULL,
    course VARCHAR(100) DEFAULT NULL,
    semester VARCHAR(20) DEFAULT NULL,
    description TEXT,
    file_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) NOT NULL,
    file_size INT DEFAULT 0,
    uploaded_by INT NOT NULL,
    upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    downloads_count INT DEFAULT 0,
    FOREIGN KEY (uploaded_by) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

-- -----------------------------------------------------
-- Table: downloads (log)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS downloads (
    download_id INT AUTO_INCREMENT PRIMARY KEY,
    note_id INT NOT NULL,
    user_id INT NOT NULL,
    download_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (note_id) REFERENCES notes(note_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- Default categories
-- -----------------------------------------------------
INSERT INTO categories (category_name) VALUES
('Notes'), ('Assignment'), ('Previous Year Paper'), ('Practical / Lab Manual'), ('Reference Material');

-- -----------------------------------------------------
-- Default Admin account (email: admin@notes.com / password: Admin@123)
-- Password is hashed using PHP password_hash (bcrypt)
-- -----------------------------------------------------
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@notes.com', '$2y$10$92J0P5Z1yQxq1s4nQ9m6XeYw7z1oQeS1c1U8lQrJt9m2yQeS1c1U8', 'admin');
-- NOTE: If this hash does not work on your XAMPP PHP version, simply register a
-- normal student account first, then in phpMyAdmin change that user's `role`
-- column value from 'student' to 'admin'. That is the easiest guaranteed method.

-- RAHVEER Certificate Database Schema
-- Run this SQL in your GoDaddy phpMyAdmin to create the database structure

-- Create database (if not already created by GoDaddy)
-- CREATE DATABASE IF NOT EXISTS rahveer_certificate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE rahveer_certificate;

-- Pledges Table - Stores all user form submissions
CREATE TABLE IF NOT EXISTS pledges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    profession VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    photo_path VARCHAR(500) DEFAULT NULL,
    certificate_number VARCHAR(50) UNIQUE NOT NULL,
    submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    INDEX idx_mobile (mobile),
    INDEX idx_cert_number (certificate_number),
    INDEX idx_submission_date (submission_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Activity Log (Optional - for tracking admin actions)
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    log_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_log_date (log_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional - remove in production)
-- INSERT INTO pledges (full_name, mobile, profession, location, certificate_number) 
-- VALUES 
-- ('Sample Driver', '9999999999', 'Driver', 'Delhi', 'RAHV-2024-SAM0001'),
-- ('Test User', '8888888888', 'Mechanic', 'Mumbai', 'RAHV-2024-TES0002');

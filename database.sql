
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



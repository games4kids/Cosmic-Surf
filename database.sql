-- Cosmic Surfer 3D Database Schema
-- Create database and users table

CREATE DATABASE IF NOT EXISTS cosmic_surfer;
USE cosmic_surfer;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    password VARCHAR(32) NOT NULL COMMENT 'MD5 hash of password',
    record INT DEFAULT 0 COMMENT 'Highest score achieved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_record (record DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert a test user (password: "test123" -> MD5: cc03e747a6afbbcbf8be7668acfebee5)
INSERT INTO users (username, email, password, record) 
VALUES ('TestPlayer', 'test@example.com', 'cc03e747a6afbbcbf8be7668acfebee5', 0)
ON DUPLICATE KEY UPDATE username=username;

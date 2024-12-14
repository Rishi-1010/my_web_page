-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS webapp_db;

-- Use the database
USE webapp_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    profile_picture VARCHAR(255) DEFAULT NULL
);

-- Add index on email for better performance
CREATE INDEX idx_email ON users(email);
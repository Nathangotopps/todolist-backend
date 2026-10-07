-- Database structure for the to-do list backend.
-- Run with:  mysql -u root -p < schema.sql
-- (To-do items themselves are stored in Firebase Realtime Database, not MySQL.)

CREATE DATABASE IF NOT EXISTS school_portal;
USE school_portal;

CREATE TABLE IF NOT EXISTS pupils (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(20) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,  -- bcrypt hash from password_hash(), never plain text
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

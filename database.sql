-- Database setup for Boarding Gigs Platform
-- Create database
CREATE DATABASE IF NOT EXISTS boarding_gigs;
USE boarding_gigs;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- Gigs table
CREATE TABLE gigs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(200) NOT NULL,
    conditions TEXT NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    photo VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO gigs (user_id, title, description, location, conditions, phone_number, photo, price, status)
VALUES



-- Payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gig_id INT NOT NULL,
    user_id INT NOT NULL,
    card_name VARCHAR(100) NOT NULL,
    card_number VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ALTER query to add phone_number field to existing gigs table
-- Run this if you already created the database without phone_number field
ALTER TABLE gigs ADD COLUMN phone_number VARCHAR(15) NOT NULL AFTER conditions;

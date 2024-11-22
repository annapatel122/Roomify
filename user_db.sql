-- Create the database
CREATE DATABASE user_db;

-- Select the database
USE user_db;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the user settings table
CREATE TABLE user_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    setting_key VARCHAR(50) NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_setting (user_id, setting_key)
);

-- Create the user_profiles table
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY,
    full_name VARCHAR(100),
    age INT,
    occupation VARCHAR(100),
    location VARCHAR(100),
    gender VARCHAR(20),
    move_in_date DATE,
    budget DECIMAL(10,2),
    bio TEXT,
    profile_picture VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE user_swipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    swiper_user_id INT NOT NULL,
    swiped_user_id INT NOT NULL,
    swipe_type ENUM('like', 'dislike') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (swiper_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (swiped_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_swipe (swiper_user_id, swiped_user_id)
);

CREATE TABLE user_matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    matched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    min_user_id INT AS (LEAST(user1_id, user2_id)) STORED,
    max_user_id INT AS (GREATEST(user1_id, user2_id)) STORED,
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_match (min_user_id, max_user_id)
);

CREATE TABLE user_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_text TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

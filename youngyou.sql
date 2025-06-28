-- Mkristoh Rental Database - youngyou.sql
-- This file contains the complete database structure and sample data

-- Create database
CREATE DATABASE IF NOT EXISTS `mkristoh_rental` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mkristoh_rental`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address` TEXT NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cars table
CREATE TABLE IF NOT EXISTS `cars` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `image_url` TEXT,
    `features` TEXT,
    `engine` TEXT,
    `available` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rentals table
CREATE TABLE IF NOT EXISTS `rentals` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL,
    `car_id` INT(11) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_price` DECIMAL(10,2) NOT NULL,
    `status` ENUM('pending', 'confirmed', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    `pickup_location` VARCHAR(100),
    `return_location` VARCHAR(100),
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`car_id`) REFERENCES `cars`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert admin user
INSERT INTO `users` (`first_name`, `last_name`, `email`, `phone`, `address`, `password`, `role`) VALUES
('Admin', 'User', 'admin@mkristohrental.com', '+1234567890', '123 Admin Street, City, Country', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample users
INSERT INTO `users` (`first_name`, `last_name`, `email`, `phone`, `address`, `password`) VALUES
('John', 'Doe', 'john.doe@example.com', '+1234567891', '123 Main Street, New York, NY 10001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Jane', 'Smith', 'jane.smith@example.com', '+1234567892', '456 Oak Avenue, Los Angeles, CA 90210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Mike', 'Johnson', 'mike.johnson@example.com', '+1234567893', '789 Pine Road, Chicago, IL 60601', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample cars with engine specifications
INSERT INTO `cars` (`name`, `category`, `price`, `image_url`, `features`, `engine`) VALUES
('Toyota Camry', 'Sedan', 50.00, 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '5 Seats, Automatic, Air Conditioning, Bluetooth, USB Port, Backup Camera', '2.5L 4-Cylinder, 203 HP, 184 lb-ft Torque, 8-Speed Automatic, FWD, 28 City / 39 Highway MPG'),
('Honda CR-V', 'SUV', 70.00, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '7 Seats, Automatic, 4WD, Navigation, Leather Seats, Sunroof', '1.5L Turbo 4-Cylinder, 190 HP, 179 lb-ft Torque, CVT Transmission, AWD, 27 City / 32 Highway MPG'),
('BMW 3 Series', 'Luxury', 120.00, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '5 Seats, Automatic, Leather Seats, Premium Sound, Navigation, Sport Mode', '2.0L Turbo 4-Cylinder, 255 HP, 295 lb-ft Torque, 8-Speed Automatic, RWD, 23 City / 33 Highway MPG'),
('Ford Mustang', 'Sports', 150.00, 'https://images.unsplash.com/photo-1582639510494-c80b5de9f148?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '4 Seats, Manual, Sports Mode, Premium Audio, Convertible, Performance Tires', '5.0L V8, 450 HP, 410 lb-ft Torque, 6-Speed Manual, RWD, 15 City / 24 Highway MPG'),
('Mercedes-Benz S-Class', 'Luxury', 200.00, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '5 Seats, Automatic, Massage Seats, Panoramic Roof, Premium Sound, Advanced Safety', '3.0L Turbo 6-Cylinder, 362 HP, 369 lb-ft Torque, 9-Speed Automatic, AWD, 20 City / 29 Highway MPG'),
('Jeep Wrangler', 'Off-Road', 80.00, 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '5 Seats, Manual, 4WD, Removable Top, Off-road Tires, Winch', '3.6L V6, 285 HP, 260 lb-ft Torque, 6-Speed Manual, 4WD, 17 City / 23 Highway MPG'),
('Tesla Model 3', 'Electric', 180.00, 'https://images.unsplash.com/photo-1536700503339-1e4b06520771?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '5 Seats, Automatic, Electric, Autopilot, Glass Roof, Supercharging', 'Dual Motor AWD, 346 HP, 389 lb-ft Torque, Single-Speed Automatic, AWD, 358 Miles Range, 0-60 in 4.2s'),
('Audi A4', 'Sedan', 90.00, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', '5 Seats, Automatic, Quattro AWD, Leather Interior, Navigation, Premium Audio', '2.0L Turbo 4-Cylinder, 201 HP, 236 lb-ft Torque, 7-Speed Automatic, AWD, 25 City / 34 Highway MPG');

-- Insert sample rentals (including some recent ones for testing cancellation)
INSERT INTO `rentals` (`user_id`, `car_id`, `start_date`, `end_date`, `total_price`, `status`, `pickup_location`, `return_location`, `created_at`) VALUES
(2, 1, '2024-01-15', '2024-01-18', 150.00, 'completed', 'main_office', 'main_office', '2024-01-10 10:00:00'),
(3, 2, '2024-02-01', '2024-02-05', 280.00, 'active', 'airport', 'airport', '2024-01-25 14:30:00'),
(4, 3, '2024-03-10', '2024-03-12', 240.00, 'pending', 'main_office', 'main_office', NOW() - INTERVAL 30 MINUTE),
(2, 4, '2024-03-15', '2024-03-17', 300.00, 'pending', 'airport', 'airport', NOW() - INTERVAL 45 MINUTE); 
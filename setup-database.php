<?php
// Database setup script for Mkristoh Rental

// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // Create connection without database
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS mkristoh_rental";
    if ($conn->query($sql) === TRUE) {
        echo "Database 'mkristoh_rental' created successfully or already exists.<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }
    
    // Select the database
    $conn->select_db('mkristoh_rental');
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' created successfully or already exists.<br>";
    } else {
        echo "Error creating users table: " . $conn->error . "<br>";
    }
    
    // Create cars table
    $sql = "CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        image_url TEXT NOT NULL,
        features TEXT,
        engine TEXT,
        available BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'cars' created successfully or already exists.<br>";
    } else {
        echo "Error creating cars table: " . $conn->error . "<br>";
    }
    
    // Create rentals table
    $sql = "CREATE TABLE IF NOT EXISTS rentals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        car_id INT NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        pickup_location VARCHAR(100) NOT NULL,
        return_location VARCHAR(100) NOT NULL,
        notes TEXT,
        status ENUM('pending', 'confirmed', 'active', 'completed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'rentals' created successfully or already exists.<br>";
    } else {
        echo "Error creating rentals table: " . $conn->error . "<br>";
    }
    
    // Check if admin user exists
    $result = $conn->query("SELECT id FROM users WHERE email = 'admin@mkristohrental.com'");
    if ($result->num_rows == 0) {
        // Create admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (first_name, last_name, email, phone, address, password, role) 
                VALUES ('Admin', 'User', 'admin@mkristohrental.com', '+254123456789', 'Admin Address', '$admin_password', 'admin')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Admin user created successfully.<br>";
            echo "Email: admin@mkristohrental.com<br>";
            echo "Password: admin123<br>";
        } else {
            echo "Error creating admin user: " . $conn->error . "<br>";
        }
    } else {
        echo "Admin user already exists.<br>";
    }
    
    // Check if sample cars exist
    $result = $conn->query("SELECT id FROM cars LIMIT 1");
    if ($result->num_rows == 0) {
        // Insert sample cars
        $cars = [
            ['Toyota Camry', 'Sedan', 50.00, 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'Air Conditioning, Bluetooth, GPS', '2.5L 4-Cylinder'],
            ['Honda CR-V', 'SUV', 65.00, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'All-Wheel Drive, Spacious Interior, Safety Features', '1.5L Turbo'],
            ['BMW 3 Series', 'Luxury', 120.00, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'Premium Audio, Leather Seats, Advanced Safety', '2.0L Turbo'],
            ['Ford Mustang', 'Sports', 150.00, 'https://images.unsplash.com/photo-1582639510494-c80b5de9f148?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'High Performance, Sport Mode, Premium Interior', '5.0L V8']
        ];
        
        foreach ($cars as $car) {
            $sql = "INSERT INTO cars (name, category, price, image_url, features, engine) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsss", $car[0], $car[1], $car[2], $car[3], $car[4], $car[5]);
            
            if ($stmt->execute()) {
                echo "Sample car '{$car[0]}' added successfully.<br>";
            } else {
                echo "Error adding sample car: " . $stmt->error . "<br>";
            }
            $stmt->close();
        }
    } else {
        echo "Sample cars already exist.<br>";
    }
    
    echo "<br>Database setup completed successfully!<br>";
    echo "<a href='index.php'>Go to Homepage</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?> 
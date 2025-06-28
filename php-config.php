<?php
// Database configuration for Mkristoh Rental
session_start();

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mkristoh_rental');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to redirect with message
function redirect_with_message($url, $message, $type = 'success') {
    $url .= (strpos($url, '?') === false ? '?' : '&') . $type . '=' . urlencode($message);
    header("Location: $url");
    exit();
}

// Function to get user by ID
function get_user_by_id($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    $sql = "SELECT id, first_name, last_name, email, phone, address, role, created_at FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get car by ID
function get_car_by_id($car_id) {
    global $conn;
    $car_id = (int)$car_id;
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get all cars
function get_all_cars() {
    global $conn;
    $sql = "SELECT * FROM cars ORDER BY name";
    $result = $conn->query($sql);
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    return $cars;
}

// Function to get user rentals
function get_user_rentals($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    $sql = "SELECT r.*, c.name as car_name, c.image_url FROM rentals r 
            JOIN cars c ON r.car_id = c.id 
            WHERE r.user_id = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rentals = [];
    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
    return $rentals;
}

// Function to get all rentals (admin)
function get_all_rentals() {
    global $conn;
    $sql = "SELECT r.*, u.first_name, u.last_name, u.email, c.name as car_name 
            FROM rentals r 
            JOIN users u ON r.user_id = u.id 
            JOIN cars c ON r.car_id = c.id 
            ORDER BY r.created_at DESC";
    $result = $conn->query($sql);
    $rentals = [];
    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
    return $rentals;
}

// Function to get dashboard statistics
function get_dashboard_stats() {
    global $conn;
    
    $stats = [];
    
    // Total users
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
    $stats['total_users'] = $result->fetch_assoc()['count'];
    
    // Total cars
    $result = $conn->query("SELECT COUNT(*) as count FROM cars");
    $stats['total_cars'] = $result->fetch_assoc()['count'];
    
    // Available cars
    $result = $conn->query("SELECT COUNT(*) as count FROM cars WHERE available = 1");
    $stats['available_cars'] = $result->fetch_assoc()['count'];
    
    // Total rentals
    $result = $conn->query("SELECT COUNT(*) as count FROM rentals");
    $stats['total_rentals'] = $result->fetch_assoc()['count'];
    
    // Active rentals
    $result = $conn->query("SELECT COUNT(*) as count FROM rentals WHERE status = 'active'");
    $stats['active_rentals'] = $result->fetch_assoc()['count'];
    
    // Pending rentals
    $result = $conn->query("SELECT COUNT(*) as count FROM rentals WHERE status = 'pending'");
    $stats['pending_rentals'] = $result->fetch_assoc()['count'];
    
    // Total revenue
    $result = $conn->query("SELECT SUM(total_price) as total FROM rentals WHERE status IN ('confirmed', 'active', 'completed')");
    $stats['total_revenue'] = $result->fetch_assoc()['total'] ?? 0;
    
    return $stats;
}
?> 
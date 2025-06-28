<?php
require_once 'php-config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $address = sanitize_input($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']) ? true : false;
    
    // Validation
    $errors = [];
    
    // Validate first name
    if (empty($first_name)) {
        $errors[] = "First name is required";
    } elseif (strlen($first_name) < 2) {
        $errors[] = "First name must be at least 2 characters long";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) {
        $errors[] = "First name can only contain letters and spaces";
    }
    
    // Validate last name
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    } elseif (strlen($last_name) < 2) {
        $errors[] = "Last name must be at least 2 characters long";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        $errors[] = "Last name can only contain letters and spaces";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Please enter a valid email address";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email address is already registered";
        }
        $stmt->close();
    }
    
    // Validate phone
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match("/^[\+]?[1-9][\d\s\-\(\)]{7,15}$/", $phone)) {
        $errors[] = "Please enter a valid phone number";
    }
    
    // Validate address
    if (empty($address)) {
        $errors[] = "Address is required";
    } elseif (strlen($address) < 10) {
        $errors[] = "Address must be at least 10 characters long";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, and one number";
    }
    
    // Validate confirm password
    if (empty($confirm_password)) {
        $errors[] = "Please confirm your password";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate terms
    if (!$terms) {
        $errors[] = "You must agree to the terms and conditions";
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $address, $hashed_password);
            
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                
                // Set session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $_SESSION['user_email'] = $email;
                $_SESSION['role'] = 'user';
                
                // Redirect to dashboard with success message
                redirect_with_message('dashboard.php', 'Registration successful! Welcome to Mkristoh Rental.');
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = "An error occurred during registration. Please try again.";
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $error_message = implode(', ', $errors);
        redirect_with_message('register.php', $error_message, 'error');
    }
    
} else {
    // If not POST request, redirect to registration page
    header("Location: register.php");
    exit();
}
?> 
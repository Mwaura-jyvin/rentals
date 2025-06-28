<?php
require_once 'php-config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $address = sanitize_input($_POST['address'] ?? '');
    
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
        // Check if email already exists (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email address is already registered by another user";
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
    
    // If no errors, proceed with update
    if (empty($errors)) {
        try {
            // Update user profile
            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                // Update session data
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $_SESSION['user_email'] = $email;
                
                // Redirect back to dashboard with success message
                header("Location: dashboard.php?success=Profile updated successfully!");
                exit();
            } else {
                $errors[] = "Failed to update profile. Please try again.";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = "An error occurred while updating your profile. Please try again.";
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $error_message = implode(', ', $errors);
        redirect_with_message('dashboard.php', $error_message, 'error');
    }
    
} else {
    // If not POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
?> 
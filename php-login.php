<?php
require_once 'php-config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validation
    $errors = [];
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no validation errors, proceed with login
    if (empty($errors)) {
        try {
            // Get user from database
            $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Set remember me cookie if requested
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 days
                        
                        // Store token in database (you might want to create a separate table for this)
                        // For simplicity, we'll just use the session
                    }
                    
                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        redirect_with_message('admin-dashboard.php', 'Welcome back, ' . $user['first_name'] . '!');
                    } else {
                        redirect_with_message('dashboard.php', 'Welcome back, ' . $user['first_name'] . '!');
                    }
                    
                } else {
                    $errors[] = "Invalid email or password";
                }
            } else {
                $errors[] = "Invalid email or password";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = "An error occurred during login. Please try again.";
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $error_message = implode(', ', $errors);
        redirect_with_message('login.php', $error_message, 'error');
    }
    
} else {
    // If not POST request, redirect to login page
    header("Location: login.php");
    exit();
}
?> 
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
    $car_id = (int)($_POST['car_id'] ?? 0);
    $start_date = sanitize_input($_POST['start_date'] ?? '');
    $end_date = sanitize_input($_POST['end_date'] ?? '');
    $pickup_location = sanitize_input($_POST['pickup_location'] ?? '');
    $return_location = sanitize_input($_POST['return_location'] ?? '');
    $notes = sanitize_input($_POST['notes'] ?? '');
    
    // Validation
    $errors = [];
    
    // Validate car ID
    if (!$car_id) {
        $errors[] = "Invalid car selection";
    } else {
        $car = get_car_by_id($car_id);
        if (!$car) {
            $errors[] = "Car not found";
        } elseif (!$car['available']) {
            $errors[] = "This car is not available for rental";
        }
    }
    
    // Validate dates
    if (empty($start_date)) {
        $errors[] = "Start date is required";
    } elseif (strtotime($start_date) < strtotime(date('Y-m-d'))) {
        $errors[] = "Start date cannot be in the past";
    }
    
    if (empty($end_date)) {
        $errors[] = "End date is required";
    } elseif (strtotime($end_date) <= strtotime($start_date)) {
        $errors[] = "End date must be after start date";
    }
    
    // Validate locations
    if (empty($pickup_location)) {
        $errors[] = "Pickup location is required";
    }
    
    if (empty($return_location)) {
        $errors[] = "Return location is required";
    }
    
    // If no validation errors, proceed with rental
    if (empty($errors)) {
        try {
            // Calculate total price
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            $days = $end->diff($start)->days;
            $total_price = $days * $car['price'];
            
            // Check if car is available for the selected dates
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM rentals 
                                   WHERE car_id = ? AND status IN ('pending', 'confirmed', 'active') 
                                   AND ((start_date <= ? AND end_date >= ?) 
                                   OR (start_date <= ? AND end_date >= ?) 
                                   OR (start_date >= ? AND end_date <= ?))");
            $stmt->bind_param("issssss", $car_id, $end_date, $start_date, $start_date, $start_date, $start_date, $end_date);
            $stmt->execute();
            $result = $stmt->get_result();
            $conflicting_rentals = $result->fetch_assoc()['count'];
            
            if ($conflicting_rentals > 0) {
                $errors[] = "This car is not available for the selected dates";
            } else {
                // Insert rental into database
                $stmt = $conn->prepare("INSERT INTO rentals (user_id, car_id, start_date, end_date, total_price, status) 
                                       VALUES (?, ?, ?, ?, ?, 'pending')");
                $stmt->bind_param("iissd", $_SESSION['user_id'], $car_id, $start_date, $end_date, $total_price);
                
                if ($stmt->execute()) {
                    $rental_id = $conn->insert_id;
                    
                    // Redirect to dashboard with success message
                    header("Location: dashboard.php?success=Rental request submitted successfully! We will review and confirm your booking.");
                    exit();
                } else {
                    $errors[] = "Failed to create rental. Please try again.";
                }
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = "An error occurred while processing your rental. Please try again.";
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $error_message = implode(', ', $errors);
        redirect_with_message('rental-form.php?car_id=' . $car_id, $error_message, 'error');
    }
    
} else {
    // If not POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
?> 
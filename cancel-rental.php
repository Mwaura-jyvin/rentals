<?php
require_once 'php-config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get rental ID
    $rental_id = (int)($_POST['rental_id'] ?? 0);
    
    if (!$rental_id) {
        redirect_with_message('dashboard.php', 'Invalid rental ID.', 'error');
    }
    
    try {
        // Get rental details and verify ownership
        $stmt = $conn->prepare("SELECT r.*, c.name as car_name FROM rentals r 
                               JOIN cars c ON r.car_id = c.id 
                               WHERE r.id = ? AND r.user_id = ? AND r.status = 'pending'");
        $stmt->bind_param("ii", $rental_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            redirect_with_message('dashboard.php', 'Rental not found or cannot be cancelled.', 'error');
        }
        
        $rental = $result->fetch_assoc();
        
        // Check if rental was created within the last hour
        $rental_created = strtotime($rental['created_at']);
        $current_time = time();
        $time_difference = $current_time - $rental_created;
        $one_hour = 3600; // 1 hour in seconds
        
        if ($time_difference > $one_hour) {
            redirect_with_message('dashboard.php', 'Cannot cancel rental after 1 hour from booking.', 'error');
        }
        
        // Update rental status to cancelled
        $stmt = $conn->prepare("UPDATE rentals SET status = 'cancelled', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $rental_id);
        
        if ($stmt->execute()) {
            redirect_with_message('dashboard.php', 'Rental cancelled successfully.', 'success');
        } else {
            redirect_with_message('dashboard.php', 'Failed to cancel rental. Please try again.', 'error');
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        redirect_with_message('dashboard.php', 'An error occurred while cancelling the rental.', 'error');
    }
    
} else {
    // If not POST request, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}
?> 
<?php
require_once 'php-config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Get user data
$user = get_user_by_id($_SESSION['user_id']);

// Debug: Check if user data is retrieved correctly
if (!$user) {
    // If user data is not found, redirect to login
    session_destroy();
    header("Location: login.php?error=User data not found. Please login again.");
    exit();
}

// Debug: Check if user is admin and redirect if necessary
if ($user['role'] === 'admin') {
    header("Location: admin-dashboard.php");
    exit();
}

$user_rentals = get_user_rentals($_SESSION['user_id']);
$all_cars = get_all_cars();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mkristoh Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-car me-2"></i>Mkristoh Rental
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!
                </span>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 dashboard-sidebar p-0">
                <div class="p-3">
                    <h5 class="text-white mb-4">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </h5>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#overview" data-bs-toggle="tab">
                            <i class="fas fa-home me-2"></i>Overview
                        </a>
                        <a class="nav-link" href="#rentals" data-bs-toggle="tab">
                            <i class="fas fa-list me-2"></i>My Rentals
                        </a>
                        <a class="nav-link" href="#cars" data-bs-toggle="tab">
                            <i class="fas fa-car me-2"></i>Browse Cars
                        </a>
                        <a class="nav-link" href="#profile" data-bs-toggle="tab">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 dashboard-main p-4">
                <!-- Alerts -->
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview">
                        <h2 class="mb-4">Dashboard Overview</h2>
                        
                        <!-- Stats Cards -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card dashboard-card">
                                    <div class="dashboard-stat">
                                        <div class="dashboard-stat-icon">
                                            <i class="fas fa-car"></i>
                                        </div>
                                        <h3 class="text-primary"><?php echo count($all_cars); ?></h3>
                                        <p class="text-muted mb-0">Available Cars</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card dashboard-card">
                                    <div class="dashboard-stat">
                                        <div class="dashboard-stat-icon">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <h3 class="text-success"><?php echo count($user_rentals); ?></h3>
                                        <p class="text-muted mb-0">Total Rentals</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card dashboard-card">
                                    <div class="dashboard-stat">
                                        <div class="dashboard-stat-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h3 class="text-warning"><?php echo count(array_filter($user_rentals, function($r) { return $r['status'] === 'active'; })); ?></h3>
                                        <p class="text-muted mb-0">Active Rentals</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Rentals -->
                        <div class="card dashboard-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>Recent Rentals
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($user_rentals)): ?>
                                    <p class="text-muted text-center py-4">No rentals yet. Start by browsing our cars!</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Car</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Total Price</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach (array_slice($user_rentals, 0, 5) as $rental): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="<?php echo htmlspecialchars($rental['image_url']); ?>" 
                                                                     alt="<?php echo htmlspecialchars($rental['car_name']); ?>" 
                                                                     class="rounded me-2" style="width: 40px; height: 30px; object-fit: cover;">
                                                                <?php echo htmlspecialchars($rental['car_name']); ?>
                                                            </div>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($rental['start_date'])); ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($rental['end_date'])); ?></td>
                                                        <td>$<?php echo number_format($rental['total_price'], 2); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $rental['status'] === 'active' ? 'success' : ($rental['status'] === 'pending' ? 'warning' : 'secondary'); ?>">
                                                                <?php echo ucfirst($rental['status']); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Rentals Tab -->
                    <div class="tab-pane fade" id="rentals">
                        <h2 class="mb-4">My Rentals</h2>
                        
                        <?php if (empty($user_rentals)): ?>
                            <div class="card dashboard-card">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-car text-muted fa-3x mb-3"></i>
                                    <h4 class="text-muted">No Rentals Yet</h4>
                                    <p class="text-muted">Start your journey by renting a car from our premium fleet.</p>
                                    <a href="#cars" class="btn btn-primary" data-bs-toggle="tab">
                                        <i class="fas fa-car me-2"></i>Browse Cars
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach ($user_rentals as $rental): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card dashboard-card h-100">
                                            <img src="<?php echo htmlspecialchars($rental['image_url']); ?>" 
                                                 class="card-img-top" alt="<?php echo htmlspecialchars($rental['car_name']); ?>"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title"><?php echo htmlspecialchars($rental['car_name']); ?></h5>
                                                <p class="text-muted mb-2">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php echo date('M d, Y', strtotime($rental['start_date'])); ?> - 
                                                    <?php echo date('M d, Y', strtotime($rental['end_date'])); ?>
                                                </p>
                                                <p class="text-primary fw-bold mb-2">
                                                    $<?php echo number_format($rental['total_price'], 2); ?>
                                                </p>
                                                <span class="badge bg-<?php echo $rental['status'] === 'active' ? 'success' : ($rental['status'] === 'pending' ? 'warning' : 'secondary'); ?> mb-3">
                                                    <?php echo ucfirst($rental['status']); ?>
                                                </span>
                                                
                                                <!-- Cancel Button for Pending Rentals -->
                                                <?php if ($rental['status'] === 'pending'): ?>
                                                    <?php
                                                    $rental_created = strtotime($rental['created_at']);
                                                    $current_time = time();
                                                    $time_difference = $current_time - $rental_created;
                                                    $one_hour = 3600; // 1 hour in seconds
                                                    $remaining_time = $one_hour - $time_difference;
                                                    $can_cancel = $remaining_time > 0;
                                                    ?>
                                                    
                                                    <?php if ($can_cancel): ?>
                                                        <div class="mt-auto">
                                                            <div class="alert alert-warning py-2 mb-2">
                                                                <small class="d-block mb-1">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    Time remaining to cancel:
                                                                </small>
                                                                <div class="countdown-timer" data-remaining="<?php echo $remaining_time; ?>" data-rental-id="<?php echo $rental['id']; ?>">
                                                                    <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                                                                </div>
                                                            </div>
                                                            <form action="cancel-rental.php" method="POST" class="d-inline">
                                                                <input type="hidden" name="rental_id" value="<?php echo $rental['id']; ?>">
                                                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to cancel this rental?')">
                                                                    <i class="fas fa-times me-2"></i>Cancel Order
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="mt-auto">
                                                            <div class="alert alert-secondary py-2 mb-2">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    Cancellation time expired
                                                                </small>
                                                            </div>
                                                            <button class="btn btn-secondary w-100" disabled>
                                                                <i class="fas fa-lock me-2"></i>Cannot Cancel
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Cars Tab -->
                    <div class="tab-pane fade" id="cars">
                        <h2 class="mb-4">Available Cars</h2>
                        
                        <div class="row g-4">
                            <?php foreach ($all_cars as $car): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card car-card h-100">
                                        <img src="<?php echo htmlspecialchars($car['image_url']); ?>" 
                                             class="card-img-top" alt="<?php echo htmlspecialchars($car['name']); ?>">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($car['name']); ?></h5>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($car['category']); ?></span>
                                            </div>
                                            <p class="car-price mb-3">$<?php echo number_format($car['price'], 2); ?>/day</p>
                                            
                                            <!-- Engine Information -->
                                            <?php if (!empty($car['engine'])): ?>
                                                <div class="mb-3">
                                                    <h6 class="text-dark mb-2 small">
                                                        <i class="fas fa-cog me-1"></i>Engine
                                                    </h6>
                                                    <p class="text-muted small mb-2"><?php echo htmlspecialchars($car['engine']); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Features -->
                                            <div class="car-features mb-3">
                                                <h6 class="text-dark mb-2 small">
                                                    <i class="fas fa-star me-1"></i>Features
                                                </h6>
                                                <?php 
                                                $features = explode(', ', $car['features']);
                                                foreach (array_slice($features, 0, 3) as $feature): ?>
                                                    <span class="badge bg-light text-dark me-1 mb-1 small"><?php echo htmlspecialchars(trim($feature)); ?></span>
                                                <?php endforeach; ?>
                                                <?php if (count($features) > 3): ?>
                                                    <span class="badge bg-secondary small">+<?php echo count($features) - 3; ?> more</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="mt-auto">
                                                <?php if ($car['available']): ?>
                                                    <a href="rental-form.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">
                                                        <i class="fas fa-car me-2"></i>Rent Now
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary w-100" disabled>
                                                        <i class="fas fa-times me-2"></i>Not Available
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div class="tab-pane fade" id="profile">
                        <h2 class="mb-4">My Profile</h2>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card dashboard-card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-user me-2"></i>Profile Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="update-profile.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                                           value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                                           value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" 
                                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" 
                                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Profile
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card dashboard-card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>Account Info
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Member Since:</strong><br>
                                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                                        <p><strong>Role:</strong><br>
                                        <?php echo ucfirst($user['role']); ?></p>
                                        <p><strong>Total Rentals:</strong><br>
                                        <?php echo count($user_rentals); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html> 
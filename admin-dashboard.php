<?php
require_once 'php-config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    header("Location: login.php");
    exit();
}

// Get admin data and statistics
$admin = get_user_by_id($_SESSION['user_id']);
$all_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$all_cars = $conn->query("SELECT COUNT(*) as total FROM cars")->fetch_assoc()['total'];
$all_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals")->fetch_assoc()['total'];
$active_rentals = $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'active'")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_price) as total FROM rentals WHERE status IN ('confirmed', 'active', 'completed')")->fetch_assoc()['total'] ?? 0;

// Get recent rentals
$recent_rentals = get_all_rentals();
$recent_rentals = array_slice($recent_rentals, 0, 10);

// Get all cars for management
$cars = get_all_cars();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mkristoh Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: blur(3px);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }
        
        .admin-content {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            min-height: 100vh;
        }
        
        .admin-sidebar {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .admin-stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            color: white;
            transition: transform 0.3s ease;
        }
        
        .admin-stat-card:hover {
            transform: translateY(-5px);
        }
        
        .admin-stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .admin-stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .admin-stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
    </style>
</head>
<body>
    <!-- Blurred Background -->
    <div class="admin-hero"></div>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-car me-2"></i>Mkristoh Rental
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-crown me-1"></i>Admin: <?php echo htmlspecialchars($admin['first_name']); ?>
                </span>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="admin-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Admin Sidebar -->
                <div class="col-md-3 col-lg-2 admin-sidebar p-0">
                    <div class="p-3">
                        <h5 class="text-white mb-4">
                            <i class="fas fa-crown me-2"></i>Admin Panel
                        </h5>
                        <nav class="nav flex-column">
                            <a class="nav-link active" href="#overview" data-bs-toggle="tab">
                                <i class="fas fa-chart-line me-2"></i>Overview
                            </a>
                            <a class="nav-link" href="#users" data-bs-toggle="tab">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                            <a class="nav-link" href="#cars" data-bs-toggle="tab">
                                <i class="fas fa-car me-2"></i>Manage Cars
                            </a>
                            <a class="nav-link" href="#rentals" data-bs-toggle="tab">
                                <i class="fas fa-list me-2"></i>Manage Rentals
                            </a>
                            <a class="nav-link" href="#reports" data-bs-toggle="tab">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9 col-lg-10 p-4">
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
                            <h2 class="mb-4 text-dark">
                                <i class="fas fa-chart-line me-2"></i>Admin Overview
                            </h2>
                            
                            <!-- Stats Cards -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-3">
                                    <div class="card admin-stat-card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <h3 class="mb-1"><?php echo $all_users; ?></h3>
                                            <p class="mb-0">Total Users</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card admin-stat-card success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-car fa-2x mb-2"></i>
                                            <h3 class="mb-1"><?php echo $all_cars; ?></h3>
                                            <p class="mb-0">Total Cars</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card admin-stat-card warning">
                                        <div class="card-body text-center">
                                            <i class="fas fa-list fa-2x mb-2"></i>
                                            <h3 class="mb-1"><?php echo $all_rentals; ?></h3>
                                            <p class="mb-0">Total Rentals</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card admin-stat-card info">
                                        <div class="card-body text-center">
                                            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                                            <h3 class="mb-1">$<?php echo number_format($total_revenue, 2); ?></h3>
                                            <p class="mb-0">Total Revenue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card dashboard-card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-clock me-2"></i>Recent Rentals
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <?php if (empty($recent_rentals)): ?>
                                                <p class="text-muted text-center py-4">No rentals yet.</p>
                                            <?php else: ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Customer</th>
                                                                <th>Car</th>
                                                                <th>Dates</th>
                                                                <th>Amount</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($recent_rentals as $rental): ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo htmlspecialchars($rental['first_name'] . ' ' . $rental['last_name']); ?>
                                                                        <br><small class="text-muted"><?php echo htmlspecialchars($rental['email']); ?></small>
                                                                    </td>
                                                                    <td><?php echo htmlspecialchars($rental['car_name']); ?></td>
                                                                    <td>
                                                                        <?php echo date('M d', strtotime($rental['start_date'])); ?> - 
                                                                        <?php echo date('M d', strtotime($rental['end_date'])); ?>
                                                                    </td>
                                                                    <td>$<?php echo number_format($rental['total_price'], 2); ?></td>
                                                                    <td>
                                                                        <span class="badge bg-<?php echo $rental['status'] === 'active' ? 'success' : ($rental['status'] === 'pending' ? 'warning' : 'secondary'); ?>">
                                                                            <?php echo ucfirst($rental['status']); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-sm btn-outline-primary" onclick="updateRentalStatus(<?php echo $rental['id']; ?>)">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
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
                                <div class="col-md-4">
                                    <div class="card dashboard-card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-chart-pie me-2"></i>Quick Stats
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Active Rentals:</span>
                                                <strong><?php echo $active_rentals; ?></strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Available Cars:</span>
                                                <strong><?php echo $conn->query("SELECT COUNT(*) as total FROM cars WHERE available = 1")->fetch_assoc()['total']; ?></strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Pending Rentals:</span>
                                                <strong><?php echo $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'pending'")->fetch_assoc()['total']; ?></strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Completed Rentals:</span>
                                                <strong><?php echo $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'completed'")->fetch_assoc()['total']; ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Tab -->
                        <div class="tab-pane fade" id="users">
                            <h2 class="mb-4 text-dark">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </h2>
                            
                            <div class="card dashboard-card">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">All Users</h5>
                                    <button class="btn btn-light btn-sm" onclick="addUser()">
                                        <i class="fas fa-plus me-1"></i>Add User
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Role</th>
                                                    <th>Joined</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
                                                while ($user = $users->fetch_assoc()): 
                                                ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                                                                <?php echo ucfirst($user['role']); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(<?php echo $user['id']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cars Tab -->
                        <div class="tab-pane fade" id="cars">
                            <h2 class="mb-4 text-dark">
                                <i class="fas fa-car me-2"></i>Manage Cars
                            </h2>
                            
                            <div class="card dashboard-card">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">All Cars</h5>
                                    <button class="btn btn-light btn-sm" onclick="addCar()">
                                        <i class="fas fa-plus me-1"></i>Add Car
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <?php foreach ($cars as $car): ?>
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
                                                        
                                                        <div class="mt-auto d-flex gap-2">
                                                            <button class="btn btn-outline-primary flex-fill" onclick="editCar(<?php echo $car['id']; ?>)">
                                                                <i class="fas fa-edit me-1"></i>Edit
                                                            </button>
                                                            <button class="btn btn-outline-danger" onclick="deleteCar(<?php echo $car['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rentals Tab -->
                        <div class="tab-pane fade" id="rentals">
                            <h2 class="mb-4 text-dark">
                                <i class="fas fa-list me-2"></i>Manage Rentals
                            </h2>
                            
                            <div class="card dashboard-card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">All Rentals</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Customer</th>
                                                    <th>Car</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Total Price</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach (get_all_rentals() as $rental): ?>
                                                    <tr>
                                                        <td>#<?php echo $rental['id']; ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($rental['first_name'] . ' ' . $rental['last_name']); ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($rental['email']); ?></small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($rental['car_name']); ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($rental['start_date'])); ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($rental['end_date'])); ?></td>
                                                        <td>$<?php echo number_format($rental['total_price'], 2); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $rental['status'] === 'active' ? 'success' : ($rental['status'] === 'pending' ? 'warning' : 'secondary'); ?>">
                                                                <?php echo ucfirst($rental['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary" onclick="updateRentalStatus(<?php echo $rental['id']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports Tab -->
                        <div class="tab-pane fade" id="reports">
                            <h2 class="mb-4 text-dark">
                                <i class="fas fa-chart-bar me-2"></i>Reports & Analytics
                            </h2>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card dashboard-card">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">Revenue Overview</h5>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="text-primary">$<?php echo number_format($total_revenue, 2); ?></h3>
                                            <p class="text-muted">Total Revenue</p>
                                            <div class="progress mb-3">
                                                <div class="progress-bar bg-success" style="width: 75%"></div>
                                            </div>
                                            <small class="text-muted">75% of monthly target</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card dashboard-card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">Rental Statistics</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <h4 class="text-success"><?php echo $active_rentals; ?></h4>
                                                    <small class="text-muted">Active Rentals</small>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="text-warning"><?php echo $conn->query("SELECT COUNT(*) as total FROM rentals WHERE status = 'pending'")->fetch_assoc()['total']; ?></h4>
                                                    <small class="text-muted">Pending Rentals</small>
                                                </div>
                                            </div>
                                        </div>
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
    <script>
        // Admin-specific functions
        function addUser() {
            alert('Add user functionality will be implemented here');
        }
        
        function editUser(userId) {
            alert('Edit user functionality will be implemented here');
        }
        
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                // Implement delete functionality
                alert('Delete user functionality will be implemented here');
            }
        }
        
        function addCar() {
            alert('Add car functionality will be implemented here');
        }
        
        function editCar(carId) {
            alert('Edit car functionality will be implemented here');
        }
        
        function deleteCar(carId) {
            if (confirm('Are you sure you want to delete this car?')) {
                // Implement delete functionality
                alert('Delete car functionality will be implemented here');
            }
        }
        
        function updateRentalStatus(rentalId) {
            alert('Update rental status functionality will be implemented here');
        }
    </script>
</body>
</html> 
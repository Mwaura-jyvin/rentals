<?php
require_once 'php-config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Get car ID from URL
$car_id = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;

if (!$car_id) {
    header("Location: dashboard.php");
    exit();
}

// Get car details
$car = get_car_by_id($car_id);

if (!$car) {
    header("Location: dashboard.php");
    exit();
}

// Get user details
$user = get_user_by_id($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Car - Mkristoh Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-car me-2"></i>Mkristoh Rental
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-car me-2"></i>Rent Car
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Car Details -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <img src="<?php echo htmlspecialchars($car['image_url']); ?>" 
                                     class="img-fluid rounded" alt="<?php echo htmlspecialchars($car['name']); ?>">
                            </div>
                            <div class="col-md-8">
                                <h5 class="card-title"><?php echo htmlspecialchars($car['name']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($car['category']); ?></p>
                                <h4 class="text-primary mb-3">$<?php echo number_format($car['price'], 2); ?>/day</h4>
                                
                                <!-- Engine Information -->
                                <?php if (!empty($car['engine'])): ?>
                                    <div class="mb-3">
                                        <h6 class="text-dark mb-2">
                                            <i class="fas fa-cog me-1"></i>Engine Specifications
                                        </h6>
                                        <div class="bg-light p-3 rounded">
                                            <p class="mb-0 text-muted small"><?php echo htmlspecialchars($car['engine']); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Features -->
                                <div class="mb-3">
                                    <h6 class="text-dark mb-2">
                                        <i class="fas fa-star me-1"></i>Features
                                    </h6>
                                    <?php 
                                    $features = explode(', ', $car['features']);
                                    foreach ($features as $feature): ?>
                                        <span class="badge bg-light text-dark me-1 mb-1"><?php echo htmlspecialchars(trim($feature)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Rental Form -->
                        <form action="process-rental.php" method="POST" id="rentalForm">
                            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pickup_location" class="form-label">Pickup Location</label>
                                <select class="form-control" id="pickup_location" name="pickup_location" required>
                                    <option value="">Select pickup location</option>
                                    <option value="main_office">Main Office - 123 Rental Street</option>
                                    <option value="airport">Airport Terminal</option>
                                    <option value="hotel">Hotel Pickup (specify in notes)</option>
                                    <option value="other">Other Location (specify in notes)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="return_location" class="form-label">Return Location</label>
                                <select class="form-control" id="return_location" name="return_location" required>
                                    <option value="">Select return location</option>
                                    <option value="main_office">Main Office - 123 Rental Street</option>
                                    <option value="airport">Airport Terminal</option>
                                    <option value="hotel">Hotel Drop-off (specify in notes)</option>
                                    <option value="other">Other Location (specify in notes)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Special Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Any special requests or additional information..."></textarea>
                            </div>

                            <!-- Price Calculation -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Price Breakdown</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <span>Daily Rate:</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span>$<?php echo number_format($car['price'], 2); ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span>Number of Days:</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span id="numDays">0</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row fw-bold">
                                        <div class="col-6">
                                            <span>Total Price:</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span id="totalPrice">$0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check me-2"></i>Confirm Rental
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Price calculation
        const dailyRate = <?php echo $car['price']; ?>;
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const numDaysSpan = document.getElementById('numDays');
        const totalPriceSpan = document.getElementById('totalPrice');

        function calculatePrice() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (startDate && endDate && endDate > startDate) {
                const timeDiff = endDate.getTime() - startDate.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                const totalPrice = daysDiff * dailyRate;
                
                numDaysSpan.textContent = daysDiff;
                totalPriceSpan.textContent = '$' + totalPrice.toFixed(2);
            } else {
                numDaysSpan.textContent = '0';
                totalPriceSpan.textContent = '$0.00';
            }
        }

        startDateInput.addEventListener('change', function() {
            // Set minimum end date to start date + 1 day
            const minEndDate = new Date(this.value);
            minEndDate.setDate(minEndDate.getDate() + 1);
            endDateInput.min = minEndDate.toISOString().split('T')[0];
            
            // If end date is before new minimum, clear it
            if (endDateInput.value && new Date(endDateInput.value) <= new Date(this.value)) {
                endDateInput.value = '';
            }
            
            calculatePrice();
        });

        endDateInput.addEventListener('change', calculatePrice);

        // Form validation
        document.getElementById('rentalForm').addEventListener('submit', function(e) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (endDate <= startDate) {
                e.preventDefault();
                alert('End date must be after start date');
                return false;
            }
            
            if (!startDateInput.value || !endDateInput.value) {
                e.preventDefault();
                alert('Please select both start and end dates');
                return false;
            }
        });
    </script>
</body>
</html> 
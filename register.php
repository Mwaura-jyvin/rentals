<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mkristoh Rental</title>
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
                <a class="nav-link" href="index.php">Back to Home</a>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus text-primary fa-3x mb-3"></i>
                            <h3 class="fw-bold">Create Account</h3>
                            <p class="text-muted">Join Mkristoh Rental today</p>
                        </div>

                        <?php if(isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_GET['success']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="php-register.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               placeholder="Enter first name" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               placeholder="Enter last name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="Enter your email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="Enter phone number" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <textarea class="form-control" id="address" name="address" rows="3" 
                                              placeholder="Enter your address" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Enter password" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Confirm password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted">
                                Already have an account? 
                                <a href="login.php" class="text-decoration-none">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 